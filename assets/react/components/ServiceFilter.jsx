import React, { useState, useEffect } from 'react';

// --- CONFIGURACIÓN ESTÁTICA ---
// Definimos un diccionario para no repetir estilos. 
// Cada tipo de servicio tiene su propio icono, nombre legible y color temático.
const TYPE_CONFIG = {
    tour:        { icon: '🗺️', label: 'Tours',          color: 'amber'   },
    therapy:     { icon: '💆', label: 'Terapias',        color: 'emerald' },
    despedida:   { icon: '🎉', label: 'Despedidas',      color: 'purple'  },
    sponsorship: { icon: '💚', label: 'Apadrinamientos', color: 'rose'    },
    service:     { icon: '⭐', label: 'Servicios',       color: 'sky'     },
};

// Mapeo de colores de Tailwind para los Badges (etiquetas pequeñas)
const BADGE_COLORS = {
    amber:   'bg-amber-100 text-amber-800',
    emerald: 'bg-emerald-100 text-emerald-800',
    purple:  'bg-purple-100 text-purple-800',
    rose:    'bg-rose-100 text-rose-800',
    sky:     'bg-sky-100 text-sky-800',
};

// Mapeo de colores para fondos suaves (detrás del icono)
const BG_COLORS = {
    amber:   'bg-amber-50',
    emerald: 'bg-emerald-50',
    purple:  'bg-purple-50',
    rose:    'bg-rose-50',
    sky:     'bg-sky-50',
};

// Mapeo de colores para el texto del icono
const TEXT_COLORS = {
    amber:   'text-amber-600',
    emerald: 'text-emerald-600',
    purple:  'text-purple-600',
    rose:    'text-rose-600',
    sky:     'text-sky-600',
};

// --- COMPONENTE PRINCIPAL ---
export default function ServiceFilter() {
    // ESTADOS:
    const [services, setServices] = useState([]);      // Lista completa de la API
    const [activeFilter, setActiveFilter] = useState('all'); // Filtro de tipo seleccionado
    const [loading, setLoading] = useState(true);      // Estado de carga
    const [searchTerm, setSearchTerm] = useState('');  // Texto de la barra de búsqueda

    // CARGA DE DATOS: Se ejecuta una sola vez al montar el componente
    useEffect(() => {
        fetch('/api/services')
            .then(res => res.json())
            .then(data => {
                setServices(data);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, []);

    // LÓGICA DE FILTRADO: 
    // 1. Extraemos qué tipos existen en los datos actuales (usando Set para no repetir)
    const availableTypes = [...new Set(services.map(s => s.type))];

    // 2. Filtramos la lista según el botón activo y el texto buscado
    const filtered = services.filter(s => {
        const matchType = activeFilter === 'all' || s.type === activeFilter;
        const matchSearch = !searchTerm ||
            (s.name || s.type || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
            s.description.toLowerCase().includes(searchTerm.toLowerCase());
        return matchType && matchSearch;
    });

    // Función auxiliar para obtener la config de un tipo (o el por defecto)
    const getConfig = (type) => TYPE_CONFIG[type] || TYPE_CONFIG.service;

    // Pantalla de carga (Spinner)
    if (loading) {
        return (
            <div className="flex items-center justify-center py-20">
                <div className="flex flex-col items-center gap-4">
                    <div className="w-12 h-12 border-4 border-[#8B5E3C] border-t-transparent rounded-full animate-spin"></div>
                    <p className="text-stone-400 font-medium">Cargando servicios...</p>
                </div>
            </div>
        );
    }

    return (
        <div>
            {/* BARRA SUPERIOR: Filtros de botones + Input de búsqueda */}
            <div className="bg-white rounded-2xl shadow-lg p-4 md:p-5 flex flex-col md:flex-row items-center gap-4 border border-stone-100 mb-12">
                <div className="flex flex-wrap items-center justify-center gap-2 flex-1">
                    {/* Botón especial para "Todos" */}
                    <FilterButton
                        active={activeFilter === 'all'}
                        onClick={() => setActiveFilter('all')}
                        label="Todos"
                        icon="✨"
                        count={services.length}
                    />
                    {/* Botones dinámicos según los tipos que vengan de la base de datos */}
                    {availableTypes.map(type => {
                        const cfg = getConfig(type);
                        return (
                            <FilterButton
                                key={type}
                                active={activeFilter === type}
                                onClick={() => setActiveFilter(type)}
                                label={cfg.label}
                                icon={cfg.icon}
                                count={services.filter(s => s.type === type).length}
                            />
                        );
                    })}
                </div>

                {/* Buscador de texto con icono de lupa y botón para borrar (X) */}
                <div className="relative w-full md:w-64">
                    <input
                        type="text"
                        placeholder="Buscar servicio..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#4A90A4]/30 focus:border-[#4A90A4] transition-all"
                    />
                    <svg className="w-4 h-4 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    {searchTerm && (
                        <button
                            onClick={() => setSearchTerm('')}
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600"
                        >
                            ✕
                        </button>
                    )}
                </div>
            </div>

            {/* Contador de resultados */}
            <div className="mb-4 text-sm text-stone-400 font-medium">
                {filtered.length} {filtered.length === 1 ? 'servicio encontrado' : 'servicios encontrados'}
            </div>

            {/* GRID: Aquí se muestran las tarjetas de los servicios filtrados */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {filtered.map((service, index) => (
                    <ServiceCard key={service.id} service={service} index={index} />
                ))}
            </div>

            {/* ESTADO VACÍO: Si no hay resultados, mostramos este aviso */}
            {filtered.length === 0 && (
                <div className="text-center py-20">
                    <p className="text-6xl mb-4">🔍</p>
                    <p className="text-stone-400 text-lg font-medium">No se encontraron servicios</p>
                    <p className="text-stone-300 text-sm mt-1">Prueba con otro filtro o término de búsqueda</p>
                    <button
                        onClick={() => { setActiveFilter('all'); setSearchTerm(''); }}
                        className="mt-4 px-6 py-2 bg-[#8B5E3C] text-white rounded-xl text-sm font-bold hover:bg-[#764f32] transition-all"
                    >
                        Mostrar todos
                    </button>
                </div>
            )}
        </div>
    );
}

// --- SUB-COMPONENTE: BOTÓN DE FILTRO ---
function FilterButton({ active, onClick, label, icon, count }) {
    return (
        <button
            onClick={onClick}
            className={`px-4 py-2 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-1.5 ${
                active
                    ? 'bg-[#764F32] text-white shadow-md shadow-[#764F32]/20 scale-105'
                    : 'text-stone-500 hover:bg-stone-50 hover:text-stone-700'
            }`}
        >
            <span>{icon}</span>
            <span>{label}</span>
            {/* Burbuja pequeña con el número de servicios de ese tipo */}
            <span className={`text-[10px] px-1.5 py-0.5 rounded-full font-bold ${
                active ? 'bg-white/20 text-white' : 'bg-stone-100 text-stone-400'
            }`}>
                {count}
            </span>
        </button>
    );
}

// --- SUB-COMPONENTE: TARJETA DE SERVICIO ---
function ServiceCard({ service, index }) {
    // Buscamos la configuración visual según el tipo de servicio
    const cfg = TYPE_CONFIG[service.type] || TYPE_CONFIG.service;
    const color = cfg.color;
    const title = service.name || cfg.label;

    return (
        <a
            href={`/service/${service.id}`}
            className="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-xl transition-all duration-300 group block relative overflow-hidden"
            // Retraso de animación en cascada según el índice
            style={{ animationDelay: `${index * 80}ms` }}
        >
            {/* ETIQUETA (Badge) de tipo en la esquina superior derecha */}
            <span className={`${BADGE_COLORS[color]} text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider absolute top-6 right-6`}>
                {cfg.label}
            </span>

            {/* CONTENEDOR DEL ICONO: Cambia de tamaño con el hover de la tarjeta */}
            <div className={`w-14 h-14 ${BG_COLORS[color]} ${TEXT_COLORS[color]} rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform`}>
                {cfg.icon}
            </div>

            {/* Título del servicio */}
            <h3 className="text-xl font-bold text-[#3E2F28] mb-3 pr-20">
                {title}
            </h3>

            {/* Descripción con recorte de texto si es muy larga (line-clamp-3) */}
            <p className="text-stone-500 text-sm leading-relaxed mb-6 line-clamp-3">
                {service.description.length > 120
                    ? service.description.slice(0, 120) + '…'
                    : service.description}
            </p>

            {/* METADATOS: Duración y Aforo máximo (este último se oculta si es apadrinamiento) */}
            <div className="flex items-center gap-6 text-xs text-stone-400 font-medium">
                <span className="flex items-center gap-1.5">
                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    {service.duration}h
                </span>
                {service.type !== 'sponsorship' && (
                    <span className="flex items-center gap-1.5">
                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        Máx. {service.maxAphor}
                    </span>
                )}
            </div>

            {/* PIE DE TARJETA: Precio formateado y enlace con flecha animada */}
            <div className="mt-6 pt-5 border-t border-stone-100 flex items-center justify-between">
                <span className="text-2xl font-bold text-[#3E2F28]">
                    {service.basePrice.toFixed(2).replace('.', ',')}€
                </span>
                <span className="text-[#4A90A4] text-sm font-bold group-hover:translate-x-1 transition-transform flex items-center gap-1">
                    Ver detalles
                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>
        </a>
    );
}
