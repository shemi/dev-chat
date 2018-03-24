import Resource from './Resource';

class ConversationResource extends Resource {

    static URL = '/conversations/{id?}';

}

export default ConversationResource;