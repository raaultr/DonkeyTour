import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // Definimos los valores que recibimos desde el HTML (ID y Nombre del burrito)
    static values = { donkeyId: String, donkeyNombre: String }

    // Método que se activa al hacer clic en el botón "Apadrinar"
    adopt(event) {
        // Evitamos que el enlace recargue la página o haga el comportamiento por defecto
        event.preventDefault();
        
        // 1. Leemos la lista actual de apadrinados del LocalStorage. 
        // Si no existe nada, empezamos con un array vacío [].
        let list = JSON.parse(localStorage.getItem('donkey_sponsored')) || [];

        // 2. Comprobamos si este burrito NO está ya en la lista
        if (!list.includes(this.donkeyIdValue)) {
            // 3. Añadimos el ID del nuevo burrito apadrinado
            list.push(this.donkeyIdValue);

            // 4. Guardamos la lista actualizada de nuevo en el LocalStorage convertido a texto (JSON)
            localStorage.setItem('donkey_sponsored', JSON.stringify(list));
            
            // 5. Llamamos a la función para cambiar el aspecto del botón al instante
            this.updateButtonVisuals();

            setTimeout(() => {
                window.location.href = '/sponsorship';
            }, 1000);
        }
    }

    // Cambia el diseño del botón para dar feedback visual al usuario
    updateButtonVisuals() {
        // 1. Cambiamos el texto
        this.element.innerHTML = `✨ ${this.donkeyNombreValue} Apadrinado`;
        
        // 2. Quitamos TODAS las clases relacionadas con el color original
        // Importante: quita también posibles efectos hover que bloqueen el verde
        this.element.classList.remove('bg-[#8B5E3C]', 'hover:bg-[#6F4B30]');
        
        // 3. Añadimos el color verde y quitamos el puntero
        // Usamos ! para forzar que Tailwind aplique el color si hay conflictos (important)
        this.element.className += " !bg-emerald-600 !text-white cursor-default shadow-none";
        
        // 4. Deshabilitamos
        this.element.disabled = true;
    }
}