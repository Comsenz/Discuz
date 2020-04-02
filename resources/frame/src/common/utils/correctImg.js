import Exif from 'exif-js';

export async function correctImg(file) {
    const or = await getImageTag(file, 'Orientation');
    // console.log(or,'or');
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onloadend = function (e) {

            const result = e.target.result;
            // console.log(result)
            const img = new Image();
            img.src = result;
            img.onload = function () {
                const file = getRotateImg(img, or);
                resolve(file);
            };
        };
    })
}

export function getImageTag(file, tag) {
    if (!file) return 0;
    return new Promise((resolve, reject) => {
        Exif.getData(file, function () {
            const o = Exif.getTag(this, tag);
            resolve(o);
        });
    });
};

function getRotateImg(img, or) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    // 图片原始大小
    const width = img.width;
    const height = img.height;
    canvas.width = width;
    canvas.height = height;
    ctx.drawImage(img, 0, 0, width, height);

    switch (or) {
        case 6: // 顺时针旋转90度
            return rotateImg(img, 'right', canvas);
        case 8: // 逆时针旋转90度
            return rotateImg(img, 'left', canvas);
        case 3: // 顺时针旋转180度
            return rotateImg(img, 'right', canvas, 2);
        default:
            break;
    }
}

export function rotateImg(img, dir = 'right', canvas, s = 1) {
    const MIN_STEP = 0;
    const MAX_STEP = 3;

    const width = canvas.width || img.width;
    const height = canvas.height || img.height;
    let step = 0;

    if (dir === 'right') {
        step += s;
        step > MAX_STEP && (step = MIN_STEP);
    } else {
        step -= s;
        step < MIN_STEP && (step = MAX_STEP);
    }

    const degree = step * 90 * Math.PI / 180;
    const ctx = canvas.getContext('2d');

    switch (step) {
        case 1:
            canvas.width = height;
            canvas.height = width;
            ctx.rotate(degree);
            ctx.drawImage(img, 0, -height, width, height);
            return dataURLtoFile(canvas.toDataURL('image/png'))
        case 2:
            canvas.width = width;
            canvas.height = height;
            ctx.rotate(degree);
            ctx.drawImage(img, -width, -height, width, height);
            return dataURLtoFile(canvas.toDataURL('image/png'))
        case 3:
            canvas.width = height;
            canvas.height = width;
            ctx.rotate(degree);
            ctx.drawImage(img, -width, 0, width, height);
            return dataURLtoFile(canvas.toDataURL('image/png'))
        default:
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);
            return dataURLtoFile(canvas.toDataURL('image/png'))
    }
};

function dataURLtoFile(dataUrl, fileName = 'avatarImg') {
    const filename = `img${Date.now()}`;
    const arr = dataUrl.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
}