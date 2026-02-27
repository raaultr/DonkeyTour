import React, { useState, useEffect } from 'react';

const TYPE_CONFIG = {
    tour:        { icon: '🗺️', label: 'Tours',          color: 'amber'   },
    therapy:     { icon: '💆', label: 'Terapias',        color: 'emerald' },
    despedida:   { icon: '🎉', label: 'Despedidas',      color: 'purple'  },
    sponsorship: { icon: '💚', label: 'Apadrinamientos', color: 'rose'    },
    service:     { icon: '⭐', label: 'Servicios',       color: 'sky'     },
};

const BADGE_COLORS = {
    amber:   'bg-amber-100 text-amber-800',
    emerald: 'bg-emerald-100 text-emerald-800',
    purple:  'bg-purple-100 text-purple-800',
    rose:    'bg-rose-100 text-rose-800',
    sky:     'bg-sky-100 text-sky-800',
};

const BG_COLORS = {
    amber:   'bg-amber-50',
    emerald: 'bg-emerald-50',
    purple:  'bg-purple-50',
    rose:    'bg-rose-50',
    sky:     'bg-sky-50',
};

const TEXT_COLORS = {
    amber:   'text-amber-600',
    emerald: 'text-emerald-600',
    purple:  'text-purple-600',
    rose:    'text-rose-600',
    sky:     'text-sky-600',
};

export default function ServiceFilter() {
    const [services, setServices] = useState([]);
    const [activeFilter, setActiveFilter] = useState('all');
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        fetch('/api/services')
            .then(res => res.json())
            .then(data => {
                setServices(data);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, []);

    // Tipos disponibles dinámicamente
    const availableTypes = [...new Set(services.map(s => s.type))];

    const filtered = services.filter(s => {
        const matchType = activeFilter === 'all' || s.type === activeFilter;
        const matchSearch = !searchTerm ||
            (s.name || s.type || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
            s.description.toLowerCase().includes(searchTerm.toLowerCase());
        return matchType && matchSearch;
    });

    const getConfig = (type) => TYPE_CONFIG[type] || TYPE_CONFIG.service;

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
            {/* Barra de filtros + búsqueda */}
            <div className="bg-white rounded-2xl shadow-lg p-4 md:p-5 flex flex-col md:flex-row items-center gap-4 border border-stone-100 mb-12">
                <div className="flex flex-wrap items-center justify-center gap-2 flex-1">
                    <FilterButton
                        active={activeFilter === 'all'}
                        onClick={() => setActiveFilter('all')}
                        label="Todos"
                        icon="✨"
                        count={services.length}
                    />
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

            {/* Resultados */}
            <div className="mb-4 text-sm text-stone-400 font-medium">
                {filtered.length} {filtered.length === 1 ? 'servicio encontrado' : 'servicios encontrados'}
            </div>

            {/* Grid de servicios */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {filtered.map((service, index) => (
                    <ServiceCard key={service.id} service={service} index={index} />
                ))}
            </div>

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
            <span className={`text-[10px] px-1.5 py-0.5 rounded-full font-bold ${
                active ? 'bg-white/20 text-white' : 'bg-stone-100 text-stone-400'
            }`}>
                {count}
            </span>
        </button>
    );
}

function ServiceCard({ service, index }) {
    const cfg = TYPE_CONFIG[service.type] || TYPE_CONFIG.service;
    const color = cfg.color;
    const title = service.name || cfg.label;

    return (
        <a
            href={`/service/${service.id}`}
            className="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-xl transition-all duration-300 group block relative overflow-hidden"
            style={{ animationDelay: `${index * 80}ms` }}
        >
            {/* Badge tipo */}
            <span className={`${BADGE_COLORS[color]} text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider absolute top-6 right-6`}>
                {cfg.label}
            </span>

            {/* Icono */}
            <div className={`w-14 h-14 ${BG_COLORS[color]} ${TEXT_COLORS[color]} rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform`}>
                {cfg.icon}
            </div>

            {/* Título */}
            <h3 className="text-xl font-bold text-[#3E2F28] mb-3 pr-20">
                {title}
            </h3>

            {/* Descripción */}
            <p className="text-stone-500 text-sm leading-relaxed mb-6 line-clamp-3">
                {service.description.length > 120
                    ? service.description.slice(0, 120) + '…'
                    : service.description}
            </p>

            {/* Meta */}
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

            {/* Precio + CTA */}
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
