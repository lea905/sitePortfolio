import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container'];
    static values = {
        index: Number,
        prototype: String
    };

    add(event) {
        event.preventDefault();
        
        const content = this.prototypeValue.replace(/__name__/g, this.indexValue);
        
        const wrapper = document.createElement('div');
        wrapper.className = 'collection-item mb-3 p-3 glass-card d-flex align-items-center gap-3';
        wrapper.innerHTML = `
            <div class="flex-grow-1">
                ${content}
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" data-action="collection#remove">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        this.containerTarget.appendChild(wrapper);
        this.indexValue++;
    }

    remove(event) {
        event.preventDefault();
        const item = event.target.closest('.collection-item');
        if (item) {
            item.remove();
        }
    }
}
