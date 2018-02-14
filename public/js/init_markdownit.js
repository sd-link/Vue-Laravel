        var md = window.markdownit({
            html:         true,        // Enable HTML tags in source
            breaks:       false,        // Convert '\n' in paragraphs into <br>
            linkify:      true,        // Autoconvert URL-like text to links

            // Enable some language-neutral replacement + quotes beautification
            typographer:  true,
            spellChecker: false,

        }).use(window.markdownitSub).use(window.markdownitSup).use(window.markdownitFootnote).use(window.markdownitDeflist).use(window.markdownitAbbr).use(window.markdownitLinkifyImages, {
                    imgClass: 'img-responsive',
                    target: '_blank',
                });