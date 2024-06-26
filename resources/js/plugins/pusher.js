import Pusher from 'pusher-js';

export function connectToChannel(channelName) {
    let connection = new Pusher("dbe0bce2baf196b73d4d", {
        cluster: "sa1",
        activityTimeout: 45000//ping server after 45 sec to check if connection still alive
    });

    return connection.subscribe(channelName);
}

export function disconnectFromChannel (connection, channelName) {
    return connection.unsubscribe(channelName);
}