importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js");

var config = {
	apiKey: "AIzaSyB8WJ01N-oaN4zt_Z6UvhT3BDxXZ-f0UEc",
	authDomain: "santache-f6f9e.firebaseapp.com",
	databaseURL: "https://santache-f6f9e-default-rtdb.firebaseio.com/",
	projectId: "santache-f6f9e",
	storageBucket: "santache-f6f9e.firebasestorage.app",
	messagingSenderId: "231312360199",
    appId: "1:231312360199:web:1a4fc06fedadf39c7cbba2",
    measurementId: "G-QQS790V9N8",
};

firebase.initializeApp(config);

notification = [];
icon = '';
base_url = '';
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {

  notification = JSON.parse(payload.data.data);
  icon = notification.icon;
  base_url = notification.base_url;

  if (notification.type == 'message') {
    var picture = notification.title;
    var message = notification.body;
    var from_id_fmc = notification.from_id;

    // single user msg
    if (notification.chat_type == 'person') {
      const notificationTitle = picture;
      const notificationOptions = {
        body: message,
        icon: icon
      };
      return self.registration.showNotification(notificationTitle, notificationOptions);
    } else {
      // group user msg
      const notificationTitle = picture;
      const notificationOptions = {
        body: message,
        icon: icon
      };
      return self.registration.showNotification(notificationTitle, notificationOptions);
    }
  }
});

self.addEventListener('notificationclick', function (event) {
  event.waitUntil(
    self.clients
      .matchAll({
        type: 'window',
        includeUncontrolled: true,
      })
      .then(function (clientList) {
        for (var i = 0; i < clientList.length; ++i) {
          var client = clientList[i];

          if (
            (client.url === base_url && 'focus' in client) ||
            (client.url === base_url + '#' && 'focus' in client) ||
            (client.url === base_url + '/' && 'focus' in client)
          ) {
            return client.focus();
          }
        }

        if (self.clients.openWindow) {
          return self.clients.openWindow(base_url);
        }
      })
  );
});