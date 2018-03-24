import Convertor from 'emoji-js';

class EmojiConvertor extends Convertor {

    constructor() {
        super();

        this.img_sets.google = {
            'path' : '/emoji-data/64/',
            'sheet' : '/emoji-data/sheets/20.png',
            'sheet_size' : 20,
            'mask' : 2
        };

        this.img_set = 'google';
        this.use_sheet = false;
        this.colons_mode = false;
        this.text_mode = false;
        this.include_title = true;
        this.include_text = false;
        this.supports_css = false;
    }



}


export default new EmojiConvertor;