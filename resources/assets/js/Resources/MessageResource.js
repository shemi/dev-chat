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

    updateStatuses(ids, conversationId) {
        return this.sync('post', {ids}, {
            keys: {
                conversationId: conversationId,
                action: 'update-status'
            }
        });
    }

}

export default MessageResource;