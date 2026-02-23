import React, { useState, useEffect } from 'react';

export default function App() {
    // 1. CARGAR: Intentamos leer la reserva del LocalStorage al arrancar
    const [reserva, setReserva] = useState(() => {
        const guardado = localStorage.getItem('donkey_reserva_temporal');
        return guardado ? JSON.parse(guardado) : { burroId: null, nombreBurro: '', precio: 0 };
    });

    // 2. GUARDAR: Cada vez que cambie la reserva, se guarda sola
    useEffect(() => {
        localStorage.setItem('donkey_reserva_temporal', JSON.stringify(reserva));
    }, [reserva]);

    const seleccionarBurro = (nombre, precio) => {
        setReserva({ burroId: Date.now(), nombreBurro: nombre, precio: precio });
    };

    return (
        /* Usamos vuestra clase .auth-card para que combine */
        <div className="auth-card mx-auto">
            <div className="auth-logo">
                <span className="auth-logo-icon">🫏</span>
                <h2 className="auth-logo-title uppercase">Tu Alquiler</h2>
                <p className="auth-logo-subtitle">Persistencia con LocalStorage</p>
            </div>

            <div className="space-y-4">
                <div className="auth-field">
                    <label>Selecciona tu compañero</label>
                    <div className="flex gap-2">
                        <button
                            onClick={() => seleccionarBurro('Platero', 25)}
                            className="auth-btn text-xs py-2"
                        >
                            Platero
                        </button>
                        <button
                            onClick={() => seleccionarBurro('Lucero', 30)}
                            className="auth-btn text-xs py-2"
                        >
                            Lucero
                        </button>
                    </div>
                </div>

                {/* Mostrar estado del LocalStorage */}
                <div className="p-4 rounded-xl bg-white/5 border border-white/10 mt-4">
                    <p className="text-white/70 text-sm italic">
                        {reserva.nombreBurro
                            ? `Has seleccionado a: ${reserva.nombreBurro} (${reserva.precio}€)`
                            : "No hay burros seleccionados todavía."
                        }
                    </p>
                </div>

                {reserva.nombreBurro && (
                    <button
                        onClick={() => setReserva({ burroId: null, nombreBurro: '', precio: 0 })}
                        className="auth-link text-xs w-full text-center block mt-4"
                    >
                        <a>Vaciar selección</a>
                    </button>
                )}
            </div>
        </div>
    );
}
