import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // Definimos el 'target' donde se inyectará el HTML de los diplomas
    static targets = ["container"]
    
    // Recibimos el nombre del usuario desde el HTML (útil para personalizar el diploma)
    static values = { userName: String }

    // Se ejecuta automáticamente cuando el controlador se conecta al DOM
    connect() {
        this.renderList();
    }

    // Función principal para dibujar la lista de diplomas
    async renderList() {
        // 1. Obtenemos los IDs guardados en el navegador. Si no hay nada, usamos un array vacío []
        const ids = JSON.parse(localStorage.getItem('donkey_sponsored')) || [];
        
        // 2. Si no hay apadrinados, mostramos un mensaje de "Lista vacía" y salimos de la función
        if (ids.length === 0) {
            this.containerTarget.innerHTML = `
                <div class="col-span-full text-center py-32 bg-white/30 rounded-[3rem] border-2 border-dashed border-stone-200 print:hidden">
                    <p class="font-serif italic text-stone-400 text-xl text-balance">El archivo de honores está esperando tu primer gran gesto...</p>
                </div>`;
            return;
        }

        try {
            // 3. Llamada asíncrona a la API para obtener los datos de todos los burritos
            const response = await fetch('/api/donkeys');
            const donkeys = await response.json();
            
            // 4. Filtramos: solo nos quedamos con los burritos cuyo ID esté en nuestra lista de LocalStorage
            const sponsored = donkeys.filter(d => ids.includes(d.id.toString()));
            
            // 5. Ajustamos las clases del contenedor: 
            // En la web es un Grid (cuadrícula), pero al imprimir pasa a ser un Bloque para que no se corten
            this.containerTarget.className = "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 items-start print:block print:gap-0";
            
            // 6. Mapeamos cada burrito al template del diploma y los unimos en un solo string de HTML
            this.containerTarget.innerHTML = sponsored.map(donkey => this.diplomaTemplate(donkey)).join('');
        } catch (e) {
            // Si la API falla o hay un error de red, mostramos un mensaje de error
            this.containerTarget.innerHTML = `<p class="col-span-full text-center text-rose-600 print:hidden">Error en el archivo central.</p>`;
        }
    }

    // Método para eliminar un apadrinamiento
    remove(event) {
        // Extraemos el ID del burrito desde el atributo 'data-id' del botón pulsado
        const id = event.currentTarget.dataset.id;

        // Pedimos confirmación al usuario antes de borrar
        if (confirm('¿Deseas finalizar el apadrinamiento oficial de este burrito?')) {
            // 1. Recuperamos la lista actual
            let list = JSON.parse(localStorage.getItem('donkey_sponsored')) || [];
            
            // 2. Creamos una nueva lista excluyendo el ID que queremos borrar
            list = list.filter(item => item.toString() !== id.toString());
            
            // 3. Sobrescribimos el LocalStorage con la nueva lista filtrada
            localStorage.setItem('donkey_sponsored', JSON.stringify(list));
            
            // 4. Refrescamos la vista llamando a renderList() para que el diploma desaparezca al instante
            this.renderList();
        }
    }

    diplomaTemplate(donkey) {
        const today = new Date().toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
        const nombrePadrino = this.userNameValue;

        return `
            <div class="flex flex-col items-center group print:m-0 print:p-0">
                <div class="diploma-card bg-[#FCF9F2] p-1.5 shadow-2xl border-[3px] border-[#3E2F28] relative w-full overflow-hidden 
                            [print-color-adjust:exact] -webkit-print-color-adjust:exact
                            print:fixed print:inset-0 print:z-[9999] print:bg-white print:border-none print:shadow-none 
                            print:flex print:items-center print:justify-center print:w-screen print:h-screen print:m-0">
                    
                    <div class="border-[1px] border-[#8B5E3C] p-8 relative overflow-hidden bg-white/40 h-full w-full flex flex-col justify-between 
                                print:w-[190mm] print:h-[270mm] print:border-2 print:p-12 print:bg-[#FCF9F2]">
                        
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-[0.03] pointer-events-none transform -rotate-12">
                            <h4 class="text-9xl font-serif font-black uppercase">DONKEY</h4>
                        </div>

                        <header class="flex justify-between items-start border-b border-[#8B5E3C]/20 pb-4">
                            <div class="text-left leading-none">
                                <p class="text-[7px] font-black text-[#8B5E3C] uppercase">Serie Documental</p>
                                <p class="text-[9px] font-serif font-bold text-[#3E2F28]">DT-2026-${donkey.id}X</p>
                            </div>
                            <span class="font-serif text-[#8B5E3C] uppercase tracking-[0.4em] text-[10px] font-bold">Certificado de Honor</span>
                        </header>

                        <div class="relative flex flex-col mt-5 items-center text-center">
                            <div class="relative mb-8 p-1 bg-white shadow-lg border border-stone-200 print:shadow-none">
                                <img src="${donkey.photoUrl}" class="w-32 h-32 object-cover">
                                <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-[#D4AF37] rounded-full border-4 border-double border-[#B8860B] flex items-center justify-center [print-color-adjust:exact]">
                                    <span class="text-white text-[7px] font-black">CERT</span>
                                </div>
                            </div>

                            <p class="font-serif text-sm text-[#5D4A41] mb-2 italic">Hágase saber por la presente que</p>
                            <h3 class="text-5xl text-[#3E2F28] mb-4" style="font-family: 'Brush Script MT', 'Dancing Script', cursive;">
                                ${nombrePadrino}
                            </h3>
                            
                            <p class="font-serif text-sm text-[#5D4A41] max-w-[300px] leading-relaxed mx-auto print:max-w-none print:text-sm">
                                ha contraído un vínculo noble y permanente para la salvaguarda de nuestro ejemplar
                            </p>
                            
                            <h4 class="text-4xl font-serif font-black text-[#8B5E3C] mt-6 mb-2 uppercase tracking-tighter">${donkey.nombre}</h4>
                        </div>

                        <footer class="w-full flex justify-between items-end px-2 pt-8">
                            <div class="text-center">
                                <p class="text-[11px] font-serif italic text-[#3E2F28]">DonkeyTour Sanctuary</p>
                                <div class="w-20 h-px bg-[#3E2F28]/20 my-1 mx-auto"></div>
                                <span class="text-[7px] uppercase tracking-widest text-stone-400">Santuario Oficial</span>
                            </div>
                            <div class="text-center">
                                <p class="text-[11px] font-serif font-bold text-[#3E2F28]">${today}</p>
                                <div class="w-20 h-px bg-[#3E2F28]/20 my-1 mx-auto"></div>
                                <span class="text-[7px] uppercase tracking-widest text-stone-400">Fecha de Registro</span>
                            </div>
                        </footer>
                    </div>
                </div>

                <div class="mt-6 flex gap-8 print:hidden">
                    <button onclick="window.print()" class="text-[9px] font-black text-stone-500 hover:text-emerald-600 uppercase tracking-widest transition-colors">
                        🖨️ Archivo Físico
                    </button>
                    <button data-action="click->sponsorship-list#remove" data-id="${donkey.id}" class="text-[9px] font-black text-stone-500 hover:text-rose-600 uppercase tracking-widest transition-colors">
                        ✕ Revocar Acta
                    </button>
                </div>
            </div>
        `;
    }
}