<?php

Broadcast::channel('conversation.{id}', \App\Broadcasting\ConversationChannel::class);
