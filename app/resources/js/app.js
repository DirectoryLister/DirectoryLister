import Alpine from 'alpinejs';
import hljs from 'highlight.js';

// Alpine.js components
import Application from './components/Application.js';
import FileInfoModal from './components/FileInfoModal.js';

Alpine.store('theme', 'light');

Alpine.data('application', Application);
Alpine.data('fileInfoModal', FileInfoModal);

// Run Alpine.js
Alpine.start();

// Highlight.js
hljs.highlightAll();
