import { ref, h, shallowRef, onMounted, onUpdated } from 'vue';
import { Modal } from 'bootstrap/js/src';


export default {
    setup() {
        const component = shallowRef({className: ''});
        const root = ref(null);
        let modal;

        onMounted(() => {
            modal = new Modal(root.value);
        });
        const show = function(component) {
            this.component = component;

            setTimeout(() => {
                modal.show('in');
            })
        };

        return { show, component, root };
    },
    render() {
        return (
            <div ref="root" className="ModalManager modal fade">
                <div className={"Modal modal-dialog "+this.component.className}>
                    {this.component && h(this.component)}
                </div>
            </div>
        );
    },
}
