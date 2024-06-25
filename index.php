<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Service Worker</title>
    <link rel="icon" type="image/png" href="./construction-worker.png" />
    <link rel="manifest" href="./manifest.json" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      crossorigin="anonymous"
    />
  </head>
  <body>
    <nav class="navbar bg-body-tertiary">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Service Worker</span>
      </div>
    </nav>
    <div class="container-fluid mt-3">
      <div
        class="d-flex justify-content-md-start justify-content-center align-items-center gap-3"
      >
        <form action="./demo_send.php" method="post">
          <input
            type="text"
            class="form-control mb-3"
            name="title"
            placeholder="title"
          />
		  <textarea class="form-control mb-3" name="body" rows="4" ></textarea>
          <input
            type="text"
            class="form-control mb-3"
            name="attachment"
            placeholder="icon url"
          />
          <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary" name="send">
              Send message
            </button>
            <button
              type="button"
              class="btn btn-success"
              onclick="allow_notification()"
            >
              Allow Notification
            </button>
          </div>
        </form>
      </div>
	  <div class="mt-3">
        <h5 class="mb-3">URL Parameters:</h5>
		<?php if (isset($_GET['icon']) && !empty($_GET['icon'])): ?>
          <img class="mb-3 rounded" src="<?php echo htmlspecialchars($_GET['icon']); ?>" alt="icon" style="max-width: 100px; max-height: 100px;">
        <?php endif; ?>
        <p class="m-0"><strong>Title:</strong> <?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : 'N/A'; ?></p>
        <p class="m-0"><strong>Message:</strong> <?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'N/A'; ?></p>
      </div>
    </div>
    <script>
      async function allow_notification() {
        if ("serviceWorker" in navigator && "PushManager" in window) {
          try {
            const registration = await navigator.serviceWorker.register("./service-worker.js");
            const serviceWorker = await navigator.serviceWorker.ready;
            const permission = await Notification.requestPermission();
            if (permission === "granted") {
              const subscription = await serviceWorker.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: "",
              });
              const response = await fetch("./subscription.php", {
                method: "POST",
                body: JSON.stringify(subscription),
                headers: {
                  "Content-Type": "application/json",
                },
              });
              const data = await response.json();
            } else {}
          } catch (error) {}
        } else {}
      }
    </script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
