import Resource from './Resource';

class MessageResource extends Resource {

    static URL = '/conversations/{conversationId}/message/{id?}/{action?}';

    send(message) {
        return this.sync('post', message, {
            keys: {
                conversationId: message.conversationId
            }
        });
    }

}

export default MessageResource;