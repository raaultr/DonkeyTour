import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    // Definimos los valores que recibiremos desde Twig
    static values = {
        audio: String
    }

    onSubmit(event) {
        // 1. Detenemos el envío inmediato del formulario
        event.preventDefault();

        // 2. Pedimos confirmación
        if (confirm('¿Estás seguro de que quieres eliminar a este protagonista?')) {
            
            // (Opcional) Deshabilitar el botón para evitar doble clic y doble rebuzno
            const btn = this.element.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            // 3. Reproducimos el audio usando el valor pasado desde Twig
            const audio = new Audio(this.audioValue);
            
            audio.play().then(() => {
                console.log("Rebuzno sonando...");
            }).catch(error => {
                console.error("Error al reproducir el audio:", error);
                // Si falla el audio (ej. políticas del navegador), enviamos el formulario igual
                this.element.submit(); 
            });

            // 4. Esperamos 1.8 segundos y enviamos el formulario real
            setTimeout(() => {
                this.element.submit();
            }, 1800);
            
        } else {
            // Si el usuario cancela, nos aseguramos de no hacer nada.
            console.log("Eliminación cancelada.");
        }
    }
}
