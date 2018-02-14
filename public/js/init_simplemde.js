function initSimpleMde(elementname, options=null)
{
    if(options)
        toolbarOptions = options;
    else
        toolbarOptions = ["heading-1", "heading-2", "heading-3", "|",  "bold", "italic", "horizontal-rule", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "side-by-side", "fullscreen"]

    var simplemde = new SimpleMDE({
        element: document.getElementById(elementname),
        forceSync: true,
        spellChecker: false,
        toolbar: toolbarOptions,
    });

    return simplemde;
}
