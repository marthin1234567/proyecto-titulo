import 'flowbite';

window.agAddToCart = (id) => {
    document.dispatchEvent(
        new CustomEvent('add-to-cart', { detail: { id: String(id) }, bubbles: true })
    );
};
