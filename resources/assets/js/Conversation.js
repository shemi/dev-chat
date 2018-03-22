
class Conversation {

    constructor(attributes) {
        this.setId(attributes.conversationId);
        this.name = attributes.name;
        this.lastMessage = attributes.lastMessage;
        this.lastMessageAt = attributes.lastMessage;
        this.contacts = attributes.contacts;
        this.messages = attributes.messages;
        this.image = attributes.image;
        this.isGroup = attributes.isGroup;
    }

    setId(id) {
        if(! id) {
            this.conversationId = null;

            return this;
        }

        this.conversationId = id;
        this.connect();

        return this;
    }

    connect() {
        console.log('connecting');
    }

    static create(attributes) {
        return new Conversation(attributes);
    }

    static createFromUser(user) {
        return Conversation.create({
            'conversationId': null,
            'name': user.name,
            'lastMessage': '',
            'lastMessageAt': new Date(),
            'contacts': [user],
            'messages': [],
            'image': user.image,
            'isGroup': false
        });
    }
}

export default Conversation;