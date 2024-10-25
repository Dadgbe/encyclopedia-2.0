document.addEventListener('DOMContentLoaded', function() {
    const editors = document.querySelectorAll('.editor');
    const boldButtons = document.querySelectorAll('[id^="bold-btn-"]');
    const italicButtons = document.querySelectorAll('[id^="italic-btn-"]');
    const underlineButtons = document.querySelectorAll('[id^="underline-btn-"]');
    const ulButtons = document.querySelectorAll('[id^="ul-btn-"]');
    const olButtons = document.querySelectorAll('[id^="ol-btn-"]');
    const linkButtons = document.querySelectorAll('[id^="link-btn-"]');

    function applyFormat(command, editorId) {
        const editor = document.getElementById(editorId);
        if (editor) {
            editor.focus();
            document.execCommand(command, false, null);
        }
    }

    boldButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => applyFormat('bold', editorId));
    });

    italicButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => applyFormat('italic', editorId));
    });

    underlineButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => applyFormat('underline', editorId));
    });

    ulButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => applyFormat('insertUnorderedList', editorId));
    });
    olButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => applyFormat('insertOrderedList', editorId));
    });


    linkButtons.forEach(button => {
        const editorId = button.id.split('-').slice(2).join('-').replace('btn-', 'editor');
        button.addEventListener('click', () => insertLink(editorId));
    });
});
