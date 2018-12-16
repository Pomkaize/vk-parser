<?php

namespace VK\Actions;

use VK\Client\VKApiRequest;
use VK\Exceptions\VKClientException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\Api\VKApiMessagesUserBlockedException;
use VK\Exceptions\Api\VKApiMessagesDenySendException;
use VK\Exceptions\Api\VKApiMessagesPrivacyException;
use VK\Exceptions\Api\VKApiMessagesKeyboardInvalidException;
use VK\Exceptions\Api\VKApiMessagesChatBotFeatureException;
use VK\Exceptions\Api\VKApiMessagesForwardAmountExceededException;
use VK\Exceptions\Api\VKApiMessagesTooLongMessageException;
use VK\Exceptions\Api\VKApiMessagesChatUserNoAccessException;
use VK\Exceptions\Api\VKApiMessagesForwardException;
use VK\Exceptions\Api\VKApiMessagesTooBigException;
use VK\Exceptions\Api\VKApiMessagesEditKindDisallowedException;
use VK\Exceptions\Api\VKApiFloodException;
use VK\Exceptions\Api\VKApiLimitsException;
use VK\Exceptions\Api\VKApiUploadException;
use VK\Exceptions\Api\VKApiPhotoChangedException;
use VK\Actions\Enums\MessagesGetConversationsFilter;
use VK\Actions\Enums\MessagesGetHistoryRev;
use VK\Actions\Enums\MessagesGetHistoryAttachmentsMediaType;
use VK\Actions\Enums\MessagesGetConversationMembersNameCase;

class Messages {

    /**
     * @var VKApiRequest
     */
    private $request;

    /**
     * Messages constructor.
     * @param VKApiRequest $request
     */
    public function __construct(VKApiRequest $request) {
        $this->request = $request;
    }

    /**
     * Returns a list of the current user's conversations.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID (for group messages with group access token)
     *      - integer offset: Offset needed to return a specific subset of conversations.
     *      - integer count: Number of conversations to return.
     *      - MessagesGetConversationsFilter filter: Filter to apply: 'all' — all conversations, 'unread' —
     *        conversations with unread messages, 'important' — conversations, marked as important (only for community
     *        messages), 'unanswered' — conversations, marked as unanswered (only for community messages)
     *        @see MessagesGetConversationsFilter
     *      - boolean extended: '1' — return extra information about users and communities
     *      - integer start_message_id: ID of the message from what to return dialogs.
     *      - array fields: Profile and communities fields to return.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getConversations(string $access_token, array $params = array()) {
        return $this->request->post('messages.getConversations', $access_token, $params);
    }

    /**
     * Returns conversations by their IDs
     *
     * @param $access_token string
     * @param $params array
     *      - array peer_ids: Destination IDs. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - boolean extended: Return extended properties
     *      - array fields: Profile and communities fields to return.
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getConversationsById(string $access_token, array $params = array()) {
        return $this->request->post('messages.getConversationsById', $access_token, $params);
    }

    /**
     * Returns messages by their IDs.
     *
     * @param $access_token string
     * @param $params array
     *      - array message_ids: Message IDs.
     *      - integer preview_length: Number of characters after which to truncate a previewed message. To preview
     *        the full message, specify '0'. "NOTE: Messages are not truncated by default. Messages are truncated by
     *        words."
     *      - boolean extended: Information whether the response should be extended
     *      - array fields: Profile fields to return.
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getById(string $access_token, array $params = array()) {
        return $this->request->post('messages.getById', $access_token, $params);
    }

    /**
     * Returns messages by their IDs within the conversation.
     *
     * @param $access_token string
     * @param $params array
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - array conversation_message_ids: Conversation message IDs.
     *      - boolean extended: Information whether the response should be extended
     *      - array fields: Profile fields to return.
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getByConversationMessageId(string $access_token, array $params = array()) {
        return $this->request->post('messages.getByConversationMessageId', $access_token, $params);
    }

    /**
     * Returns a list of the current user's private messages that match search criteria.
     *
     * @param $access_token string
     * @param $params array
     *      - string q: Search query string.
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - integer date: Date to search message before in Unixtime.
     *      - integer preview_length: Number of characters after which to truncate a previewed message. To preview
     *        the full message, specify '0'. "NOTE: Messages are not truncated by default. Messages are truncated by
     *        words."
     *      - integer offset: Offset needed to return a specific subset of messages.
     *      - integer count: Number of messages to return.
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function search(string $access_token, array $params = array()) {
        return $this->request->post('messages.search', $access_token, $params);
    }

    /**
     * Returns message history for the specified user or group chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer offset: Offset needed to return a specific subset of messages.
     *      - integer count: Number of messages to return.
     *      - integer user_id: ID of the user whose message history you want to return.
     *      - integer peer_id:
     *      - integer start_message_id: Starting message ID from which to return history.
     *      - boolean extended: Information whether the response should be extended
     *      - array fields: Profile fields to return.
     *      - integer group_id: Group ID (for group messages with group access token)
     *      - MessagesGetHistoryRev rev: Sort order: '1' — return messages in chronological order. '0' — return
     *        messages in reverse chronological order.
     *        @see MessagesGetHistoryRev
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getHistory(string $access_token, array $params = array()) {
        return $this->request->post('messages.getHistory', $access_token, $params);
    }

    /**
     * Returns media files from the dialog or group chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer peer_id: Peer ID. ", For group chat: '2000000000 + chat ID' , , For community: '-community
     *        ID'"
     *      - MessagesGetHistoryAttachmentsMediaType media_type: Type of media files to return: *'photo',,
     *        *'video',, *'audio',, *'doc',, *'link'.,*'market'.,*'wall'.,*'share'
     *        @see MessagesGetHistoryAttachmentsMediaType
     *      - string start_from: Message ID to start return results from.
     *      - integer count: Number of objects to return.
     *      - boolean photo_sizes: '1' — to return photo sizes in a
     *      - array fields: Additional profile [vk.com/dev/fields|fields] to return. 
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getHistoryAttachments(string $access_token, array $params = array()) {
        return $this->request->post('messages.getHistoryAttachments', $access_token, $params);
    }

    /**
     * Sends a message.
     *
     * @param $access_token string
     * @param $params array
     *      - integer user_id: User ID (by default — current user).
     *      - integer random_id: Unique identifier to avoid resending the message.
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - string domain: User's short address (for example, 'illarionov').
     *      - integer chat_id: ID of conversation the message will relate to.
     *      - array user_ids: IDs of message recipients (if new conversation shall be started).
     *      - string message: (Required if 'attachments' is not set.) Text of the message.
     *      - number lat: Geographical latitude of a check-in, in degrees (from -90 to 90).
     *      - number long: Geographical longitude of a check-in, in degrees (from -180 to 180).
     *      - array attachment: (Required if 'message' is not set.) List of objects attached to the message,
     *        separated by commas, in the following format: "<owner_id>_<media_id>", '' — Type of media attachment:
     *        'photo' — photo, 'video' — video, 'audio' — audio, 'doc' — document, 'wall' — wall post,
     *        '<owner_id>' — ID of the media attachment owner. '<media_id>' — media attachment ID. Example:
     *        "photo100172_166443618"
     *      - string forward_messages: ID of forwarded messages, separated with a comma. Listed messages of the
     *        sender will be shown in the message body at the recipient's. Example: "123,431,544"
     *      - integer sticker_id: Sticker id.
     *      - boolean notification: '1' if the message is a notification (for community messages).
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     * @throws VKApiMessagesUserBlockedException Can't send messages for users from blacklist
     * @throws VKApiMessagesDenySendException Can't send messages for users without dialogs
     * @throws VKApiMessagesPrivacyException Can't send messages to this user due to their privacy settings
     * @throws VKApiMessagesKeyboardInvalidException Keyboard format is invalid
     * @throws VKApiMessagesChatBotFeatureException This is a chat bot feature, change this status in settings
     * @throws VKApiMessagesForwardAmountExceededException Too many forwarded messages
     * @throws VKApiMessagesTooLongMessageException Message is too long
     * @throws VKApiMessagesChatUserNoAccessException You don't have access to this chat
     * @throws VKApiMessagesForwardException Can't forward these messages
     *
     */
    public function send(string $access_token, array $params = array()) {
        return $this->request->post('messages.send', $access_token, $params);
    }

    /**
     * Edits the message.
     *
     * @param $access_token string
     * @param $params array
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - string message: (Required if 'attachments' is not set.) Text of the message.
     *      - number lat: Geographical latitude of a check-in, in degrees (from -90 to 90).
     *      - number long: Geographical longitude of a check-in, in degrees (from -180 to 180).
     *      - array attachment: (Required if 'message' is not set.) List of objects attached to the message,
     *        separated by commas, in the following format: "<owner_id>_<media_id>", '' — Type of media attachment:
     *        'photo' — photo, 'video' — video, 'audio' — audio, 'doc' — document, 'wall' — wall post,
     *        '<owner_id>' — ID of the media attachment owner. '<media_id>' — media attachment ID. Example:
     *        "photo100172_166443618"
     *      - boolean keep_forward_messages: '1' — to keep forwarded, messages.
     *      - boolean keep_snippets: '1' — to keep attached snippets.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     * @throws VKApiMessagesDenySendException Can't send messages for users without dialogs
     * @throws VKApiMessagesPrivacyException Can't send messages to this user due to their privacy settings
     * @throws VKApiMessagesTooBigException Can't sent this message, because it's too big
     * @throws VKApiMessagesKeyboardInvalidException Keyboard format is invalid
     * @throws VKApiMessagesTooLongMessageException Message is too long
     * @throws VKApiMessagesChatUserNoAccessException You don't have access to this chat
     * @throws VKApiMessagesEditKindDisallowedException Can't edit this kind of message
     *
     */
    public function edit(string $access_token, array $params = array()) {
        return $this->request->post('messages.edit', $access_token, $params);
    }

    /**
     * Deletes one or more messages.
     *
     * @param $access_token string
     * @param $params array
     *      - array message_ids: Message IDs.
     *      - boolean spam: '1' — to mark message as spam.
     *      - boolean delete_for_all: '1' — delete message for for all.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function delete(string $access_token, array $params = array()) {
        return $this->request->post('messages.delete', $access_token, $params);
    }

    /**
     * Deletes all private messages in a conversation.
     *
     * @param $access_token string
     * @param $params array
     *      - string user_id: User ID. To clear a chat history use 'chat_id'
     *      - integer group_id: Group ID (for group messages with user access token)
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - integer offset: Offset needed to delete a specific subset of conversations.
     *      - integer count: Number of conversations to delete. "NOTE: If the number of messages exceeds the
     *        maximum, the method shall be called several times."
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function deleteConversation(string $access_token, array $params = array()) {
        return $this->request->post('messages.deleteConversation', $access_token, $params);
    }

    /**
     * Restores a deleted message.
     *
     * @param $access_token string
     * @param $params array
     *      - integer message_id: ID of a previously-deleted message to restore.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function restore(string $access_token, array $params = array()) {
        return $this->request->post('messages.restore', $access_token, $params);
    }

    /**
     * Marks messages as read.
     *
     * @param $access_token string
     * @param $params array
     *      - array message_ids: IDs of messages to mark as read.
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - integer start_message_id: Message ID to start from.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function markAsRead(string $access_token, array $params = array()) {
        return $this->request->post('messages.markAsRead', $access_token, $params);
    }

    /**
     * Marks and unmarks messages as important (starred).
     *
     * @param $access_token string
     * @param $params array
     *      - array message_ids: IDs of messages to mark as important.
     *      - boolean important: '1' — to add a star (mark as important), '0' — to remove the star
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function markAsImportant(string $access_token, array $params = array()) {
        return $this->request->post('messages.markAsImportant', $access_token, $params);
    }

    /**
     * Marks and unmarks conversations as important.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID (for group messages with group access token)
     *      - integer peer_id: ID of conversation to mark as important.
     *      - boolean important: '1' — to add a star (mark as important), '0' — to remove the star
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function markAsImportantConversation(string $access_token, array $params = array()) {
        return $this->request->post('messages.markAsImportantConversation', $access_token, $params);
    }

    /**
     * Marks and unmarks conversations as unanswered.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID (for group messages with group access token)
     *      - integer peer_id: ID of conversation to mark as important.
     *      - boolean answered: '1' — to mark as answered, '0' — to remove the mark
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function markAsAnsweredConversation(string $access_token, array $params = array()) {
        return $this->request->post('messages.markAsAnsweredConversation', $access_token, $params);
    }

    /**
     * Returns data required for connection to a Long Poll server.
     *
     * @param $access_token string
     * @param $params array
     *      - integer lp_version: Long poll version
     *      - boolean need_pts: '1' — to return the 'pts' field, needed for the
     *        [vk.com/dev/messages.getLongPollHistory|messages.getLongPollHistory] method.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getLongPollServer(string $access_token, array $params = array()) {
        return $this->request->post('messages.getLongPollServer', $access_token, $params);
    }

    /**
     * Returns updates in user's private messages.
     *
     * @param $access_token string
     * @param $params array
     *      - integer ts: Last value of the 'ts' parameter returned from the Long Poll server or by using
     *        [vk.com/dev/messages.getLongPollHistory|messages.getLongPollHistory] method.
     *      - integer pts: Lsat value of 'pts' parameter returned from the Long Poll server or by using
     *        [vk.com/dev/messages.getLongPollHistory|messages.getLongPollHistory] method.
     *      - integer preview_length: Number of characters after which to truncate a previewed message. To preview
     *        the full message, specify '0'. "NOTE: Messages are not truncated by default. Messages are truncated by
     *        words."
     *      - boolean onlines: '1' — to return history with online users only.
     *      - array fields: Additional profile [vk.com/dev/fields|fields] to return.
     *      - integer events_limit: Maximum number of events to return.
     *      - integer msgs_limit: Maximum number of messages to return.
     *      - integer max_msg_id: Maximum ID of the message among existing ones in the local copy. Both messages
     *        received with API methods (for example, , ), and data received from a Long Poll server (events with code 4)
     *        are taken into account.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getLongPollHistory(string $access_token, array $params = array()) {
        return $this->request->post('messages.getLongPollHistory', $access_token, $params);
    }

    /**
     * Creates a chat with several participants.
     *
     * @param $access_token string
     * @param $params array
     *      - array user_ids: IDs of the users to be added to the chat.
     *      - string title: Chat title.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     * @throws VKApiFloodException Flood control
     *
     */
    public function createChat(string $access_token, array $params = array()) {
        return $this->request->post('messages.createChat', $access_token, $params);
    }

    /**
     * Edits the title of a chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer chat_id: Chat ID.
     *      - string title: New title of the chat.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function editChat(string $access_token, array $params = array()) {
        return $this->request->post('messages.editChat', $access_token, $params);
    }

    /**
     * Returns a list of IDs of users participating in a chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID (for group messages with group access token)
     *      - integer peer_id: Peer ID.
     *      - array fields: Profile fields to return.
     *      - MessagesGetConversationMembersNameCase name_case: Case for declension of user name and surname: 'nom'
     *        — nominative (default), 'gen' — genitive, 'dat' — dative, 'acc' — accusative, 'ins' —
     *        instrumental, 'abl' — prepositional
     *        @see MessagesGetConversationMembersNameCase
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getConversationMembers(string $access_token, array $params = array()) {
        return $this->request->post('messages.getConversationMembers', $access_token, $params);
    }

    /**
     * Changes the status of a user as typing in a conversation.
     *
     * @param $access_token string
     * @param $params array
     *      - string user_id: User ID.
     *      - string type: 'typing' — user has started to type.
     *      - integer peer_id: Destination ID. "For user: 'User ID', e.g. '12345'. For chat: '2000000000' +
     *        'chat_id', e.g. '2000000001'. For community: '- community ID', e.g. '-12345'. "
     *      - integer group_id: Group ID (for group messages with group access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function setActivity(string $access_token, array $params = array()) {
        return $this->request->post('messages.setActivity', $access_token, $params);
    }

    /**
     * Returns a list of the current user's conversations that match search criteria.
     *
     * @param $access_token string
     * @param $params array
     *      - string q: Search query string.
     *      - integer count: Maximum number of results.
     *      - boolean extended: '1' — return extra information about users and communities
     *      - array fields: Profile fields to return.
     *      - integer group_id: Group ID (for group messages with user access token)
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function searchConversations(string $access_token, array $params = array()) {
        return $this->request->post('messages.searchConversations', $access_token, $params);
    }

    /**
     * Adds a new user to a chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer chat_id: Chat ID.
     *      - integer user_id: ID of the user to be added to the chat.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     * @throws VKApiLimitsException Out of limits
     *
     */
    public function addChatUser(string $access_token, array $params = array()) {
        return $this->request->post('messages.addChatUser', $access_token, $params);
    }

    /**
     * Allows the current user to leave a chat or, if the current user started the chat, allows the user to remove
     * another user from the chat.
     *
     * @param $access_token string
     * @param $params array
     *      - integer chat_id: Chat ID.
     *      - string user_id: ID of the user to be removed from the chat.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function removeChatUser(string $access_token, array $params = array()) {
        return $this->request->post('messages.removeChatUser', $access_token, $params);
    }

    /**
     * Returns a user's current status and date of last activity.
     *
     * @param $access_token string
     * @param $params array
     *      - integer user_id: User ID.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function getLastActivity(string $access_token, array $params = array()) {
        return $this->request->post('messages.getLastActivity', $access_token, $params);
    }

    /**
     * Sets a previously-uploaded picture as the cover picture of a chat.
     *
     * @param $access_token string
     * @param $params array
     *      - string file: Upload URL from the 'response' field returned by the
     *        [vk.com/dev/photos.getChatUploadServer|photos.getChatUploadServer] method upon successfully uploading an
     *        image.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     * @throws VKApiUploadException Upload error
     * @throws VKApiPhotoChangedException Original photo was changed
     *
     */
    public function setChatPhoto(string $access_token, array $params = array()) {
        return $this->request->post('messages.setChatPhoto', $access_token, $params);
    }

    /**
     * Deletes a chat's cover picture.
     *
     * @param $access_token string
     * @param $params array
     *      - integer chat_id: Chat ID.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function deleteChatPhoto(string $access_token, array $params = array()) {
        return $this->request->post('messages.deleteChatPhoto', $access_token, $params);
    }

    /**
     * Denies sending message from community to the current user.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function denyMessagesFromGroup(string $access_token, array $params = array()) {
        return $this->request->post('messages.denyMessagesFromGroup', $access_token, $params);
    }

    /**
     * Allows sending messages from community to the current user.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function allowMessagesFromGroup(string $access_token, array $params = array()) {
        return $this->request->post('messages.allowMessagesFromGroup', $access_token, $params);
    }

    /**
     * Returns information whether sending messages from the community to current user is allowed.
     *
     * @param $access_token string
     * @param $params array
     *      - integer group_id: Group ID.
     *      - integer user_id: User ID.
     *
     * @return mixed
     * @throws VKClientException in case of network error
     * @throws VKApiException in case of API error
     *
     */
    public function isMessagesFromGroupAllowed(string $access_token, array $params = array()) {
        return $this->request->post('messages.isMessagesFromGroupAllowed', $access_token, $params);
    }
}
