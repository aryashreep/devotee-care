    </div> <!-- /container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then((reg) => console.log('Service worker registered.', reg))
                .catch((err) => console.log('Service worker not registered.', err));
        }
    </script>
</body>
</html>
