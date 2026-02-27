import React from 'react';
import ReactDOM from 'react-dom/client';
import ServiceFilter from './components/ServiceFilter';
import DonkeyCarousel from './components/DonkeyCarousel';
import { CounterGroup } from './components/AnimatedCounter';
import BackToTop from './components/BackToTop';

/**
 * Monta un componente React en un elemento del DOM si existe.
 */
function mountIfExists(elementId, Component) {
    const el = document.getElementById(elementId);
    if (el) {
        ReactDOM.createRoot(el).render(
            <React.StrictMode>
                <Component />
            </React.StrictMode>
        );
    }
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Filtro interactivo de servicios (página /service)
    mountIfExists('react-service-filter', ServiceFilter);

    // Carrusel de burros (página /home)
    mountIfExists('react-donkey-carousel', DonkeyCarousel);

    // Contadores animados (página /sobre-nosotros)
    mountIfExists('react-counters', CounterGroup);

    // Botón flotante "Volver arriba" (global)
    mountIfExists('react-back-to-top', BackToTop);
});

