import Alpine from 'alpinejs';
import hljs from 'highlight.js';

// Alpine.js components
import Application from './components/application.js';
import FileInfoModal from './components/file-info-modal.js';

Alpine.data('application', Application);
Alpine.data('file-info-modal', FileInfoModal);

// Run Alpine.js
Alpine.start();

// Highlight.js
hljs.highlightAll();
