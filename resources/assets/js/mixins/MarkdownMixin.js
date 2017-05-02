var marked = require('marked');

var MarkdownMixin = {
    methods: {
        markdown(text){
            return marked(text, {sanitize: true});
        }
    }
}

export default MarkdownMixin;