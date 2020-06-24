<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Formater;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\ThreadVideo;
use Illuminate\Support\Arr;

class PostFormater
{
    /**
     * 过滤块
     * @param Post $post
     * @return mixed
     */
    public static function pure(Post $post)
    {
        $content = $post->getAttribute('content');
        $content = json_decode($content, true);
        if (isset($content['blocks'])) {
            $content['blocks'] = self::pureBlocks($content['blocks'], $post);
        }
        return $post->content = $content;
    }

    /**
     * 过滤无权查看的块（未付费的块）
     * @param $blocks
     * @param $post
     * @return mixed
     */
    private static function pureBlocks($blocks, $post)
    {
        if (!empty($blocks)) {
            foreach ($blocks as $key => &$value) {
                $type = Arr::get($value, 'type');
                $child = Arr::get($value, 'data.child');
                if (!empty($child)) {
                    if ($type == 'pay') {
                        $actor = app('request')->getAttribute('actor');
                        if ($actor->id == $post->user_id || $actor->isAdmin()) {
                            $value['data']['status'] = true;//自己的post默认已付费
                        } else {
                            $block_payid = Arr::get($value, 'data.blockPayid');
                            $paid_status = PaidCheck::isPaid($post->id, [$block_payid]);
                            if (!$paid_status) {//不返回无权查看的块
                                $value['data']['status'] = false;//先设置未未付费
                                $pured_child = isset($value['defaultBlock']) ? $child[$value['defaultBlock']] : $child[0];
                                $value['data']['child'] = [$pured_child];
                                $value['data']['defaultBlock'] = 0;
                                continue;
                            } else {
                                $value['data']['status'] = true;//付费状态
                            }
                        }
                    }
                    $value['data']['child'] = [self::pureBlocks($child, $post)];
                }
            }
        }
        return $blocks;
    }

    /**
     * 检查付费块附件
     * @param Attachment $attachment
     * @return Attachment
     */
    public static function checkAttachment(Attachment $attachment)
    {
        $content = json_decode($attachment->post->content, true);
        $pay_blocks = self::isInPayBlock(Arr::get($content, 'blocks'), $attachment->id, ['attachment', 'image']);
        $attachment->setAttribute('pay_blocks', $pay_blocks);
        return $attachment;
    }

    /**
     * 检查付费块视频
     * @param ThreadVideo
     * @return mixed
     */
    public static function checkVodeo(ThreadVideo $video)
    {
        if (!empty($video->post_id)) {
            $content = json_decode($video->post->content, true);
        } else {
            $content = json_decode($video->thread->firstPost, true);
        }
        $pay_blocks = self::isInPayBlock(Arr::get($content, 'blocks'), $video->id, ['video']);
        $video->setAttribute('pay_blocks', $pay_blocks);
        return $video;
    }

    /**
     * 提取附件所在付费块
     * @param $blocks 块数组
     * @param $attachment_id 附件id
     * @param array $figure_types 指定块名
     * @param string $payid 付费块block_payid
     * @return array
     */
    private static function isInPayBlock($blocks, $attachment_id, $figure_types = [], $payid = '')
    {
        $values = [];
        if (!empty($blocks)) {
            foreach ($blocks as $key => $value) {
                $type = Arr::get($value, 'type');
                if ($type == 'pay') {
                    $block_payid = Arr::get($value, 'data.blockPayid');
                    $child = Arr::get($value, 'data.child');
                    if (!empty($child)) {
                        $status = self::isInPayBlock($child, $attachment_id, $figure_types, $block_payid);
                        if ($status) {
                            if (is_array($status) && !empty($status)) {
                                $values = array_merge($values, $status);
                            } else {
                                $values[] = $block_payid;
                            }
                        }
                    }
                } else {
                    if (in_array($type, $figure_types) && !empty($payid)) {
                        if (is_array($value['data']['value'])) {
                            if (in_array($attachment_id, $value['data']['value'])) {
                                $values[] = $payid;
                            }
                        } elseif ($value['data']['value'] == $attachment_id) {
                            $values[] = $payid;
                        }
                    }
                }
            }
        }
        return $values;
    }

    /**
     * 获取所有付费块id以及价格
     * @param $blocks
     * @return array
     */
    public static function getPayIds($blocks)
    {
        $values = [];
        if (!empty($blocks)) {
            foreach ($blocks as $key => $value) {
                $type = Arr::get($value, 'type');
                if ($type == 'pay') {
                    $block_payid = Arr::get($value, 'data.blockPayid');
                    $values[$block_payid] = [
                        'price' => Arr::get($value, 'data.price')
                    ];
                    $child = Arr::get($value, 'data.child');
                    if (!empty($child)) {
                        $ids = self::getPayIds($child);
                        if (is_array($ids) && !empty($ids)) {
                            $values = array_merge($values, $ids);
                        }
                    }
                }
            }
        }
        return $values;
    }
}
