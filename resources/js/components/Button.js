export default {
    render() {
        const attrs = Object.assign({}, this.$attrs);
        attrs.type = attrs.type || 'button';
        return (
        <button {...attrs}>{this.$slots['default'] && this.$slots['default']()}</button>
        );
    }
}
