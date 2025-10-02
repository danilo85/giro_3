// Variáveis globais
let carouselTextCount = 0;
console.log('social-post-form.js carregado!');
let selectedHashtags = [];
let hashtagSuggestions = [];
let currentEmojiTarget = null;

document.addEventListener('DOMContentLoaded', function() {
    // Common initialization
    updatePreview();

    // Event listeners
    const titleElement = document.getElementById('titulo');
    const contentElement = document.getElementById('legenda');
    const textoFinalElement = document.getElementById('texto_final');
    const textoFinalEditor = document.getElementById('texto_final_editor');
    const hashtagInput = document.getElementById('hashtagInput');
    const carouselTextsInput = document.getElementById('carouselTextsInput_editor');
    const legendaEditor = document.getElementById('legenda_editor');

    if (titleElement) {
        titleElement.addEventListener('input', updatePreview);
        titleElement.addEventListener('input', updateTitleCounter);
        // Initialize counter on page load
        updateTitleCounter();
    }
    if (contentElement) contentElement.addEventListener('input', updatePreview);
    if (legendaEditor) {
        legendaEditor.addEventListener('input', function() {
            syncEditorContent('legenda_editor', 'legenda');
            updatePreview();
            updateContentCounter();
        });
        // Initialize counter on page load
        updateContentCounter();
    }
    if (textoFinalElement) textoFinalElement.addEventListener('input', updatePreview);
    if (textoFinalEditor) {
        textoFinalEditor.addEventListener('input', function() {
            syncEditorContent('texto_final_editor', 'texto_final');
            updatePreview();
        });
    }
    if (hashtagInput) {
        hashtagInput.addEventListener('keydown', handleHashtagInput);
        hashtagInput.addEventListener('input', handleHashtagSearch);
    }
    if (carouselTextsInput) {
        carouselTextsInput.addEventListener('input', function() {
            syncEditorContent('carouselTextsInput_editor', 'carousel_texts_combined');
            updateCarouselPreview();
        });
    }

    // Load existing data for edit page
    if (document.body.classList.contains('page-edit-social-post')) {
        const hashtagsData = document.getElementById('hashtagsData');
        if (hashtagsData) {
            selectedHashtags = JSON.parse(hashtagsData.textContent);
            updateHashtagDisplay();
        }
    }
});

// Text formatting
function formatText(command, targetId) {
    const element = document.getElementById(targetId) || document.getElementById('content') || document.getElementById('legenda');
    if (!element) return;

    element.focus();
    
    try {
        switch (command) {
            case 'bold':
                document.execCommand('bold', false, null);
                break;
            case 'italic':
                document.execCommand('italic', false, null);
                break;
            case 'underline':
                document.execCommand('underline', false, null);
                break;
            case 'strikeThrough':
                document.execCommand('strikeThrough', false, null);
                break;
            case 'subscript':
                document.execCommand('subscript', false, null);
                break;
        }
        
        // Sincronizar com campo hidden se existir
        syncEditorContent(targetId);
        updatePreview();
    } catch (e) {
        console.error('Erro ao formatar texto:', e);
    }
}

function formatCarouselText(command) {
    const element = document.getElementById('carouselTextsInput_editor');
    if (!element) return;

    element.focus();
    
    try {
        switch (command) {
            case 'bold':
                document.execCommand('bold', false, null);
                break;
            case 'italic':
                document.execCommand('italic', false, null);
                break;
            case 'underline':
                document.execCommand('underline', false, null);
                break;
            case 'strikeThrough':
                document.execCommand('strikeThrough', false, null);
                break;
            case 'subscript':
                document.execCommand('subscript', false, null);
                break;
        }
        
        // Sincronizar com campo hidden se existir
        syncEditorContent('carouselTextsInput_editor', 'carousel_texts_combined');
        updateCarouselPreview();
    } catch (e) {
        console.error('Erro ao formatar texto do carrossel:', e);
    }
}

function formatTextFinal(command) {
    const element = document.getElementById('texto_final_editor');
    if (!element) return;

    element.focus();
    
    try {
        switch (command) {
            case 'bold':
                document.execCommand('bold', false, null);
                break;
            case 'italic':
                document.execCommand('italic', false, null);
                break;
            case 'underline':
                document.execCommand('underline', false, null);
                break;
            case 'strikeThrough':
                document.execCommand('strikeThrough', false, null);
                break;
            case 'subscript':
                document.execCommand('subscript', false, null);
                break;
        }
        
        // Sincronizar com campo hidden se existir
        syncEditorContent('texto_final_editor', 'texto_final');
        updatePreview();
    } catch (e) {
        console.error('Erro ao formatar texto final:', e);
    }
}

// Character counter functions
function updateTitleCounter() {
    const titleElement = document.getElementById('titulo');
    const counterElement = document.getElementById('titleCounter');
    
    if (!titleElement || !counterElement) return;
    
    const currentLength = titleElement.value.length;
    const maxLength = 100;
    
    counterElement.textContent = `${currentLength}/${maxLength}`;
    
    // Change color when approaching limit (90% or more)
    if (currentLength >= maxLength * 0.9) {
        counterElement.classList.add('text-red-500');
        counterElement.classList.remove('text-gray-500', 'dark:text-gray-400');
    } else {
        counterElement.classList.remove('text-red-500');
        counterElement.classList.add('text-gray-500', 'dark:text-gray-400');
    }
}

function updateContentCounter() {
    const contentElement = document.getElementById('legenda_editor');
    const counterElement = document.getElementById('contentCounter');
    
    if (!contentElement || !counterElement) return;
    
    // Get text content without HTML tags for accurate character count
    const currentLength = contentElement.textContent.length || contentElement.innerText.length || 0;
    const maxLength = 2200;
    
    counterElement.textContent = `${currentLength}/${maxLength}`;
    
    // Change color when approaching limit (90% or more)
    if (currentLength >= maxLength * 0.9) {
        counterElement.classList.add('text-red-500');
        counterElement.classList.remove('text-gray-500', 'dark:text-gray-400');
    } else {
        counterElement.classList.remove('text-red-500');
        counterElement.classList.add('text-gray-500', 'dark:text-gray-400');
    }
}

function insertEmoji(emoji) {
    const textarea = document.getElementById('content') || document.getElementById('legenda');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + emoji.length, start + emoji.length);
    updatePreview();
}

function insertEmojiToCarousel(emoji) {
    const editor = document.getElementById('carouselTextsInput_editor');
    if (!editor) return;

    editor.focus();
    
    // Inserir emoji no contenteditable
    const selection = window.getSelection();
    const range = selection.getRangeAt(0);
    
    const emojiNode = document.createTextNode(emoji);
    range.deleteContents();
    range.insertNode(emojiNode);
    
    // Posicionar cursor após o emoji
    range.setStartAfter(emojiNode);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
    
    // Sincronizar com campo hidden
    syncEditorContent('carouselTextsInput_editor', 'carousel_texts_combined');
    updateCarouselPreview();
}

function insertEmojiToFinal(emoji) {
    const editor = document.getElementById('texto_final_editor');
    if (!editor) return;

    editor.focus();
    
    // Inserir emoji no contenteditable
    const selection = window.getSelection();
    const range = selection.getRangeAt(0);
    
    const emojiNode = document.createTextNode(emoji);
    range.deleteContents();
    range.insertNode(emojiNode);
    
    // Posicionar cursor após o emoji
    range.setStartAfter(emojiNode);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
    
    // Sincronizar com campo hidden
    syncEditorContent('texto_final_editor', 'texto_final');
    updatePreview();
}

// Emoji modal functions
function showEmojiPicker(targetId) {
    currentEmojiTarget = targetId;
    const modal = document.getElementById('emojiModal');
    if (modal) {
        modal.classList.remove('hidden');
        loadEmojis();
    }
}

function closeEmojiModal() {
    const modal = document.getElementById('emojiModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    currentEmojiTarget = null;
}

function loadEmojis() {
    const emojiGrid = document.getElementById('emojiGrid');
    if (!emojiGrid) return;
    
    // Lista básica de emojis organizados por categoria
    const emojis = {
        smileys: ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳'],
        people: ['👋', '🤚', '🖐️', '✋', '🖖', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👍', '👎', '👊', '✊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏'],
        nature: ['🌱', '🌿', '🍀', '🌾', '🌵', '🌲', '🌳', '🌴', '🌸', '🌺', '🌻', '🌹', '🥀', '🌷', '💐', '🌼', '🌙', '🌛', '🌜', '🌚', '🌕', '🌖', '🌗', '🌘', '🌑', '🌒', '🌓', '🌔', '⭐', '🌟'],
        food: ['🍎', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🍈', '🍒', '🍑', '🥭', '🍍', '🥥', '🥝', '🍅', '🍆', '🥑', '🥦', '🥬', '🥒', '🌶️', '🌽', '🥕', '🧄', '🧅', '🥔', '🍠', '🥐', '🍞', '🥖'],
        activities: ['⚽', '🏀', '🏈', '⚾', '🥎', '🎾', '🏐', '🏉', '🥏', '🎱', '🪀', '🏓', '🏸', '🏒', '🏑', '🥍', '🏏', '🪃', '🥅', '⛳', '🪁', '🏹', '🎣', '🤿', '🥊', '🥋', '🎽', '🛹', '🛷', '⛸️'],
        objects: ['💎', '🔔', '🔕', '🎵', '🎶', '💰', '💴', '💵', '💶', '💷', '💸', '💳', '🧾', '💹', '💱', '💲', '✉️', '📧', '📨', '📩', '📤', '📥', '📦', '📫', '📪', '📬', '📭', '📮', '🗳️', '✏️']
    };
    
    // Carregar todos os emojis por padrão
    let allEmojis = [];
    Object.values(emojis).forEach(category => {
        allEmojis = allEmojis.concat(category);
    });
    
    emojiGrid.innerHTML = allEmojis.map(emoji => 
        `<button type="button" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded text-lg" onclick="selectEmoji('${emoji}')">${emoji}</button>`
    ).join('');
}

function selectEmoji(emoji) {
    if (currentEmojiTarget) {
        const element = document.getElementById(currentEmojiTarget);
        if (element) {
            // Verificar se é um contenteditable ou textarea
            if (element.contentEditable === 'true') {
                element.focus();
                
                // Inserir emoji no contenteditable
                const selection = window.getSelection();
                const range = selection.getRangeAt(0);
                
                const emojiNode = document.createTextNode(emoji);
                range.deleteContents();
                range.insertNode(emojiNode);
                
                // Posicionar cursor após o emoji
                range.setStartAfter(emojiNode);
                range.collapse(true);
                selection.removeAllRanges();
                selection.addRange(range);
                
                // Sincronizar com campo hidden baseado no ID
                if (currentEmojiTarget === 'carouselTextsInput_editor') {
                    syncEditorContent('carouselTextsInput_editor', 'carousel_texts_combined');
                    updateCarouselPreview();
                } else if (currentEmojiTarget === 'texto_final_editor') {
                    syncEditorContent('texto_final_editor', 'texto_final');
                    updatePreview();
                } else {
                    updatePreview();
                }
            } else {
                // Para textareas normais
                const start = element.selectionStart;
                const end = element.selectionEnd;
                element.value = element.value.substring(0, start) + emoji + element.value.substring(end);
                element.focus();
                element.setSelectionRange(start + emoji.length, start + emoji.length);
                updatePreview();
            }
        }
    }
    closeEmojiModal();
}

function filterEmojis() {
    // Função para filtrar emojis (implementação básica)
    const searchInput = document.getElementById('emojiSearch');
    if (!searchInput) return;
    
    const query = searchInput.value.toLowerCase();
    const buttons = document.querySelectorAll('#emojiGrid button');
    
    buttons.forEach(button => {
        const emoji = button.textContent;
        button.style.display = query === '' || emoji.includes(query) ? 'block' : 'none';
    });
}

function filterEmojisByCategory(category) {
    // Função para filtrar por categoria (implementação básica)
    loadEmojis(); // Recarregar todos os emojis por enquanto
}

// Função para sincronizar conteúdo do editor com campo hidden
function syncEditorContent(editorId, hiddenFieldId) {
    const editor = document.getElementById(editorId);
    const hiddenField = document.getElementById(hiddenFieldId || editorId + '_hidden');
    
    if (editor && hiddenField) {
        hiddenField.value = editor.innerHTML;
    }
}

// Global exports
window.insertEmoji = insertEmoji;
window.insertEmojiToCarousel = insertEmojiToCarousel;
window.insertEmojiToFinal = insertEmojiToFinal;
window.formatText = formatText;
window.formatCarouselText = formatCarouselText;
window.formatTextFinal = formatTextFinal;
window.updatePreview = updatePreview;
window.updateCarouselPreview = updateCarouselPreview;
window.insertCarouselDivider = insertCarouselDivider;
window.handleHashtagInput = handleHashtagInput;
window.handleHashtagSearch = handleHashtagSearch;
window.handleHashtagKeyup = handleHashtagKeyup;
window.selectHashtag = selectHashtag;
window.removeHashtag = removeHashtag;
window.addHashtag = addHashtag;
window.updateHashtagDisplay = updateHashtagDisplay;
window.saveDraft = saveDraft;
window.toggleCallToActionType = toggleCallToActionType;
window.previewCallToActionImage = previewCallToActionImage;
window.removeCurrentImage = removeCurrentImage;
window.showEmojiPicker = showEmojiPicker;
window.closeEmojiModal = closeEmojiModal;
window.selectEmoji = selectEmoji;
window.filterEmojis = filterEmojis;
window.filterEmojisByCategory = filterEmojisByCategory;
window.syncEditorContent = syncEditorContent;
window.updateTitleCounter = updateTitleCounter;
window.updateContentCounter = updateContentCounter;

// Hashtag functions
function handleHashtagSearch(e) {
    const query = e.target.value.trim();
    if (query.length > 0) {
        fetch(`/social-posts/api/hashtags/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => showHashtagSuggestions(data))
            .catch(error => console.error('Error searching hashtags:', error));
    } else {
        hideHashtagSuggestions();
    }
}

// Alias for handleHashtagSearch to maintain compatibility
function handleHashtagKeyup(e) {
    handleHashtagSearch(e);
}

function showHashtagSuggestions(hashtags) {
    const container = document.getElementById('hashtagSuggestions');
    if (!container) return;

    if (hashtags.length === 0) {
        hideHashtagSuggestions();
        return;
    }

    container.innerHTML = `
        <div class="bg-white border border-gray-200 rounded-md shadow-lg">
            ${hashtags.map(hashtag => `
                <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100 border-b border-gray-100 last:border-b-0"
                        onclick="selectHashtag('${hashtag.name}')">
                    #${hashtag.name}
                    <small class="text-muted ms-2">(${hashtag.usage_count} uses)</small>
                </button>
            `).join('')}
        </div>`;
    container.style.display = 'block';
}

function hideHashtagSuggestions() {
    const element = document.getElementById('hashtagSuggestions');
    if (element) element.style.display = 'none';
}

function selectHashtag(hashtag) {
    const hashtagInput = document.getElementById('hashtagInput');
    if(hashtagInput) hashtagInput.value = hashtag;
    addHashtag();
    hideHashtagSuggestions();
}

function handleHashtagInput(event) {
    const input = event.target;
    if (event.key === ' ' || event.key === 'Enter') {
        event.preventDefault();
        addHashtag();
    } else if (event.key === 'Backspace' && input.value === '' && selectedHashtags.length > 0) {
        removeHashtag(selectedHashtags[selectedHashtags.length - 1]);
    }
}

function addHashtag() {
    const input = document.getElementById('hashtagInput');
    if (!input) return;

    const hashtags = input.value.trim().split(/[ ,]+/).filter(Boolean);
    hashtags.forEach(hashtag => {
        const cleanHashtag = hashtag.replace(/[^a-zA-Z0-9_\u00C0-\u017F]/g, '');
        if (cleanHashtag && !selectedHashtags.includes(cleanHashtag) && selectedHashtags.length < 30) {
            selectedHashtags.push(cleanHashtag);
        }
    });

    updateHashtagDisplay();
    input.value = '';
    hideHashtagSuggestions();
}

function removeHashtag(hashtag) {
    selectedHashtags = selectedHashtags.filter(h => h !== hashtag);
    updateHashtagDisplay();
}

function updateHashtagDisplay() {
    console.log('updateHashtagDisplay chamada, selectedHashtags:', selectedHashtags);
    // Tenta encontrar o container correto (create e edit usam selectedHashtags)
    const container = document.getElementById('selectedHashtags');
    const counter = document.getElementById('hashtagCounter');
    const hiddenInput = document.getElementById('hashtags');

    console.log('Elementos encontrados:', { container, counter, hiddenInput });
    
    if (!container) {
        console.error('Container selectedHashtags não encontrado!');
        return;
    }

    if (container) {
        container.innerHTML = selectedHashtags.map(hashtag => `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mr-2 mb-2">
                #${hashtag}
                <button type="button" class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100" onclick="removeHashtag('${hashtag}')">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </span>
        `).join('');
    }

    if (counter) {
        counter.textContent = `${selectedHashtags.length}/30`;
        counter.className = selectedHashtags.length >= 30 ? 'text-sm text-red-500' : 'text-sm text-gray-500';
    }

    if (hiddenInput) {
        hiddenInput.value = selectedHashtags.join(',');
    }
    updatePreview();
}

// Carousel functions
function updateCarouselPreview() {
    const editor = document.getElementById('carouselTextsInput_editor');
    const counter = document.getElementById('carouselCounter');
    if (!editor || !counter) return;

    // Obter o conteúdo do contenteditable e filtrar slides vazios
    const content = editor.innerHTML || editor.textContent || '';
    const slides = content.split('---')
        .map(slide => slide.trim().replace(/^\n+|\n+$/g, '').replace(/<br>/g, '\n')) // Remove \n do início e fim e converte <br> para \n
        .filter(slide => slide.length > 0 && slide !== '\n');
    counter.textContent = `${slides.length} slides`;
    updatePreview();
}

function insertCarouselDivider() {
    const editor = document.getElementById('carouselTextsInput_editor');
    if (!editor) return;

    editor.focus();
    
    // Inserir o separador no contenteditable
    const selection = window.getSelection();
    const range = selection.getRangeAt(0);
    
    const dividerElement = document.createElement('div');
    dividerElement.innerHTML = '<br>---<br>';
    
    range.deleteContents();
    range.insertNode(dividerElement);
    
    // Posicionar cursor após o separador
    range.setStartAfter(dividerElement);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
    
    // Sincronizar com campo hidden
    syncEditorContent('carouselTextsInput_editor', 'carousel_texts_combined');
    updateCarouselPreview();
}

// Preview function
function updatePreview() {
    const previewElement = document.getElementById('postPreview');
    if (!previewElement) return;

    const title = document.getElementById('titulo')?.value || '';
    const legend = document.getElementById('legenda')?.value || '';
    const final_text = document.getElementById('texto_final')?.value || '';
    const carouselEditor = document.getElementById('carouselTextsInput_editor');
    const carouselContent = carouselEditor ? (carouselEditor.innerHTML || carouselEditor.textContent || '') : '';
    const carouselSlides = carouselContent
        .split('---')
        .map(s => s.trim().replace(/^\n+|\n+$/g, '').replace(/<br>/g, '\n')) // Remove \n do início e fim e converte <br> para \n
        .filter(s => s.length > 0 && s !== '\n');

    let previewHTML = '';

    if (title) previewHTML += `<div class="mb-3"><strong>📱 Título:</strong><br>${title}</div>`;
    if (legend) previewHTML += `<div class="mb-3"><strong>📝 Conteúdo:</strong><br>${legend.replace(/\n/g, '<br>')}</div>`;

    carouselSlides.forEach((slide, index) => {
        previewHTML += `<div class="mb-3"><strong>📱 Carrossel ${index + 1}:</strong><br>${slide.replace(/\n/g, '<br>')}</div>`;
    });

    if (selectedHashtags.length > 0) {
        previewHTML += `<div class="mb-3"><strong>🏷️ Hashtags:</strong><br>${selectedHashtags.map(h => `#${h}`).join(' ')}</div>`;
    }

    if (final_text) previewHTML += `<div class="mb-3"><strong>📢 Call-to-Action:</strong><br>${final_text.replace(/\n/g, '<br>')}</div>`;

    if (!previewHTML) {
        previewHTML = `
            <div class="text-gray-500 dark:text-gray-400 text-center py-3">
                <svg class="w-8 h-8 mx-auto mb-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H2ZM2 5h16v10H2V5Z"/>
                </svg>
                <p class="mb-0">Preview do post aparecerá aqui</p>
            </div>`;
    }

    previewElement.innerHTML = `<div class="social-post-preview">${previewHTML}</div>`;
}

// Form submission
function saveDraft() {
    const statusInput = document.getElementById('status');
    if (statusInput) statusInput.value = 'rascunho';
    document.getElementById('socialPostForm').submit();
}

// Call-to-Action functions
function toggleCallToActionType() {
    const textRadio = document.querySelector('input[name="call_to_action_type"][value="text"]');
    const imageRadio = document.querySelector('input[name="call_to_action_type"][value="image"]');
    const textSection = document.getElementById('text_call_to_action');
    const imageSection = document.getElementById('image_call_to_action');
    
    if (textRadio && textRadio.checked) {
        if (textSection) textSection.classList.remove('hidden');
        if (imageSection) imageSection.classList.add('hidden');
        // Limpar o input de imagem
        const imageInput = document.getElementById('call_to_action_image');
        if (imageInput) imageInput.value = '';
        resetImagePreview();
    } else if (imageRadio && imageRadio.checked) {
        if (textSection) textSection.classList.add('hidden');
        if (imageSection) imageSection.classList.remove('hidden');
        // Limpar o textarea de texto
        const textInput = document.getElementById('texto_final');
        if (textInput) textInput.value = '';
    }
    
    updatePreview();
}

function previewCallToActionImage(event) {
    const file = event.target.files[0];
    processCallToActionImage(file);
}

function processCallToActionImage(file) {
    const uploadPlaceholder = document.getElementById('upload_placeholder');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const fileName = document.getElementById('file_name');
    const imageInput = document.getElementById('call_to_action_image');
    
    if (file) {
        // Validar tipo de arquivo
        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor, selecione apenas arquivos PNG, JPG ou JPEG.');
            if (imageInput) imageInput.value = '';
            return;
        }
        
        // Validar tamanho do arquivo (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('O arquivo deve ter no máximo 2MB.');
            if (imageInput) imageInput.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) previewImg.src = e.target.result;
            if (fileName) fileName.textContent = file.name;
            if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
            if (imagePreview) imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
        
        // Atualizar o input file com o arquivo
        if (imageInput) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
        }
    } else {
        resetImagePreview();
    }
}

// Drag and Drop functionality
function initializeDragAndDrop() {
    const dropZone = document.querySelector('label[for="call_to_action_image"]');
    
    if (!dropZone) return;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        dropZone.classList.remove('border-gray-300', 'bg-gray-50', 'dark:bg-gray-700');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        dropZone.classList.add('border-gray-300', 'bg-gray-50', 'dark:bg-gray-700');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            processCallToActionImage(files[0]);
        }
    }
}

// Initialize drag and drop when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
});

function resetImagePreview() {
    const uploadPlaceholder = document.getElementById('upload_placeholder');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const fileName = document.getElementById('file_name');
    
    if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
    if (imagePreview) imagePreview.classList.add('hidden');
    if (previewImg) previewImg.src = '';
    if (fileName) fileName.textContent = '';
}

function removeCurrentImage() {
    const currentImageDiv = document.getElementById('current_image');
    const imageInput = document.getElementById('call_to_action_image');
    
    if (currentImageDiv) currentImageDiv.style.display = 'none';
    
    // Adicionar um campo hidden para indicar que a imagem deve ser removida
    let removeInput = document.getElementById('remove_call_to_action_image');
    if (!removeInput) {
        removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_call_to_action_image';
        removeInput.id = 'remove_call_to_action_image';
        removeInput.value = '1';
        if (imageInput && imageInput.parentNode) {
            imageInput.parentNode.appendChild(removeInput);
        }
    } else {
        removeInput.value = '1';
    }
}

// Funções para formatação de data brasileira
function formatDateBrazilian(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length >= 5) {
        value = value.substring(0, 5) + '/' + value.substring(5, 9);
    }
    
    input.value = value;
}

function validateDateBrazilian(input) {
    const value = input.value;
    const dateRegex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    
    if (value && !dateRegex.test(value)) {
        input.setCustomValidity('Por favor, insira uma data válida no formato dd/mm/aaaa');
        input.classList.add('border-red-500');
        return false;
    }
    
    if (value) {
        const match = value.match(dateRegex);
        if (match) {
            const day = parseInt(match[1]);
            const month = parseInt(match[2]);
            const year = parseInt(match[3]);
            
            // Validar se a data é válida
            const date = new Date(year, month - 1, day);
            if (date.getDate() !== day || date.getMonth() !== month - 1 || date.getFullYear() !== year) {
                input.setCustomValidity('Data inválida');
                input.classList.add('border-red-500');
                return false;
            }
        }
    }
    
    input.setCustomValidity('');
    input.classList.remove('border-red-500');
    return true;
}