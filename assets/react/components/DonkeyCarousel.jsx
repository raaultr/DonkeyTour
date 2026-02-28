import React, { useState, useEffect, useRef } from 'react';

export default function DonkeyCarousel() {
    // --- ESTADOS (La memoria del componente) ---
    const [donkeys, setDonkeys] = useState([]); // Guarda la lista de burros que viene de la base de datos
    const [loading, setLoading] = useState(true); // Controla si mostramos el "spinner" de carga
    const [currentIndex, setCurrentIndex] = useState(0); // El índice del burro que se ve en el centro
    const [isAutoPlaying, setIsAutoPlaying] = useState(true); // Interruptor para que se mueva solo o no
    const containerRef = useRef(null); // Referencia al div principal (por si quisiéramos medir su ancho)

    // --- CARGA DE DATOS (Se ejecuta al abrir la página) ---
    useEffect(() => {
        fetch('/api/donkeys') // Pide los datos a tu Symfony
            .then(res => res.json())
            .then(data => {
                setDonkeys(data); // Guarda los burros en el estado
                setLoading(false); // Quita la pantalla de carga
            })
            .catch(() => setLoading(false)); // Si hay error, también deja de cargar
    }, []);

    // --- AUTOPLAY (El temporizador) ---
    useEffect(() => {
        // Si el usuario lo paró o no hay burros, no hagas nada
        if (!isAutoPlaying || donkeys.length === 0) return;

        const timer = setInterval(() => {
            // Avanza al siguiente. El % asegura que al llegar al final vuelva al 0
            setCurrentIndex(prev => (prev + 1) % donkeys.length);
        }, 4000); // Cada 4 segundos

        return () => clearInterval(timer); // Limpia el reloj si el componente se destruye
    }, [isAutoPlaying, donkeys.length]);

    // --- FUNCIONES DE NAVEGACIÓN ---
    const goTo = (i) => {
        setCurrentIndex(i); // Salta al burro "i"
        setIsAutoPlaying(false); // Pausa el movimiento automático para no molestar al usuario
        setTimeout(() => setIsAutoPlaying(true), 8000); // Lo reactiva tras 8 segundos de inactividad
    };

    const prev = () => goTo((currentIndex - 1 + donkeys.length) % donkeys.length);
    const next = () => goTo((currentIndex + 1) % donkeys.length);

    // --- VISTA DE CARGA ---
    if (loading) {
        return (
            <div className="flex items-center justify-center py-16">
                <div className="w-10 h-10 border-4 border-[#8B5E3C] border-t-transparent rounded-full animate-spin"></div>
            </div>
        );
    }

    if (donkeys.length === 0) return null;

    // --- LÓGICA VISUAL (Cálculo de los 3 burros visibles) ---
    const getVisibleDonkeys = () => {
        const result = [];
        // Queremos el anterior (-1), el actual (0) y el siguiente (1)
        for (let offset = -1; offset <= 1; offset++) {
            const idx = (currentIndex + offset + donkeys.length) % donkeys.length;
            result.push({ ...donkeys[idx], offset });
        }
        return result;
    };

    const visible = getVisibleDonkeys();

    return (
        <div className="relative">
            {/* Títulos superiores */}
            <div className="text-center mb-10">
                <span className="text-[#8B5E3C] font-bold tracking-widest uppercase text-xs">Conócelos</span>
                <h2 className="text-3xl md:text-4xl font-bold text-[#3E2F28] mt-3">Nuestros Compañeros</h2>
                <p className="text-stone-400 mt-3 max-w-lg mx-auto text-sm">
                    Cada uno con su personalidad única. Desliza para conocerlos a todos.
                </p>
            </div>

            {/* Carrusel (Contenedor de las tarjetas) */}
            <div className="relative overflow-hidden" ref={containerRef}>
                <div className="flex items-center justify-center gap-6 px-4 py-4">
                    {visible.map((donkey, i) => {
                        const isCenter = donkey.offset === 0; // ¿Es el burro del medio?
                        return (
                            <div
                                key={`${donkey.id}-${donkey.offset}`}
                                className={`transition-all duration-500 ease-out flex-shrink-0 ${
                                    isCenter
                                        ? 'w-72 md:w-80 scale-100 opacity-100 z-10' // Grande y brillante
                                        : 'w-56 md:w-64 scale-90 opacity-50 hidden md:block' // Pequeño y traslúcido
                                }`}
                            >
                                <DonkeyCard donkey={donkey} isCenter={isCenter} />
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Controles inferiores (Botones y Puntos) */}
            <div className="flex items-center justify-center gap-4 mt-8">
                <button onClick={prev} className="w-10 h-10 rounded-full bg-white border border-stone-200 flex items-center justify-center hover:border-[#8B5E3C] transition-all">
                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>

                {/* Dots: Genera un punto por cada burro en la lista */}
                <div className="flex gap-2">
                    {donkeys.map((_, i) => (
                        <button
                            key={i}
                            onClick={() => goTo(i)}
                            className={`transition-all duration-300 rounded-full ${
                                i === currentIndex
                                    ? 'w-8 h-3 bg-[#8B5E3C]' // Punto alargado si es el activo
                                    : 'w-3 h-3 bg-stone-200'
                            }`}
                        />
                    ))}
                </div>

                <button onClick={next} className="w-10 h-10 rounded-full bg-white border border-stone-200 flex items-center justify-center hover:border-[#8B5E3C] transition-all">
                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {/* Enlace externo */}
            <div className="text-center mt-6">
                <a href="/donkey" className="inline-flex items-center gap-2 text-[#4A90A4] font-bold text-sm hover:underline">
                    Ver todos los burros
                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    );
}

// --- COMPONENTE HIJO (La tarjeta individual) ---
function DonkeyCard({ donkey, isCenter }) {
    const photoUrl = donkey.photoUrl || '/img/burro1.jpg'; // Imagen por defecto si no tiene

    return (
        <div className={`bg-white rounded-3xl overflow-hidden border border-stone-100 transition-all duration-500 ${
            isCenter ? 'shadow-xl' : 'shadow-sm'
        }`}>
            {/* Contenedor de la Imagen */}
            <div className="relative h-52 overflow-hidden">
                <img src={photoUrl} alt={donkey.nombre} className="w-full h-full object-cover" />
                {donkey.disponible && (
                    <span className="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                        Disponible
                    </span>
                )}
            </div>

            {/* Datos del burro */}
            <div className="p-5">
                <div className="flex items-center gap-2 mb-2">
                    <span className="text-lg">🐴</span>
                    <h3 className="text-lg font-bold text-[#3E2F28]">{donkey.nombre}</h3>
                </div>

                <div className="grid grid-cols-2 gap-2 text-xs text-stone-400">
                    {donkey.years && <div><span>🎂</span> {donkey.years} años</div>}
                    {donkey.race && <div><span>🏷️</span> {donkey.race}</div>}
                    {donkey.kilogram && <div><span>⚖️</span> {donkey.kilogram} kg</div>}
                </div>

                {/* Solo mostramos el botón si el burro está en el centro */}
                {isCenter && (
                    <a href={`/donkey/${donkey.id}`} className="block mt-4 text-center py-2.5 border-2 border-[#8B5E3C] text-[#8B5E3C] font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-[#8B5E3C] hover:text-white transition-all">
                        Conocer a {donkey.nombre}
                    </a>
                )}
            </div>
        </div>
    );
}
