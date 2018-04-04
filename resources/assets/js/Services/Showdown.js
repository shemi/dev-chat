import MarkdownIt from 'markdown-it';
import xssFilter from 'showdown-xss-filter';
import hljs from 'highlightjs';

const md = MarkdownIt();

class Showdown {

    constructor() {
        // (`{3}\s*)?(\[.*?\]\s*\()(javascript.*)(\))(\s*`{3}\s?)?

        this.converter = new MarkdownIt({
            html: false,
            linkify: true,
            typographer: true,
            breaks: true,
            quotes: '“”‘’',
            highlight: function (str, lang) {
                let highlight = "";

                try {
                    highlight = lang && hljs.getLanguage(lang) ?
                        hljs.highlight(lang, str, true).value :
                        hljs.highlightAuto(str).value;

                    lang = lang || highlight.language;
                } catch (ex) {

                }

                highlight = Showdown.decodeHTMLEntities(highlight);

                return `<pre class="highlight">
                            <code class="hljs language-${lang}">${highlight}</code>
                        </pre>`;
            }
        })
            .disable(['link', 'image']);
    }

    convert(text) {
        if (!text) {
            return "";
        }

        return this.converter.render(text);
    }

    static decodeHTMLEntities(text) {
        const entityPattern = /&([a-z]+);/ig,
            entities = {
                'amp': '&',
                'apos': '\'',
                'lt': '<',
                'gt': '>',
                'quot': '"',
                'nbsp': '\xa0'
            };

        // A single replace pass with a static RegExp is faster than a loop
        return text.replace(entityPattern, function (match, entity) {
            entity = entity.toLowerCase();
            if (entities.hasOwnProperty(entity)) {
                return entities[entity];
            }
            // return original string if there is no matching entity (no replace)
            return match;
        });

    }

}

export default new Showdown();