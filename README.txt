Seekho-style PHP API (Core PHP)

Files:
- config.php      : Database connection (edit with your DB credentials)
- helpers.php     : Common helpers (JSON response, headers, auth, method checks)
- auth_*.php      : Authentication related endpoints
- home_*.php      : Home screen endpoints
- category_*.php  : Category related endpoints
- video_*.php     : Video details and actions
- premium_*.php   : Subscription related endpoints
- trending_*.php  : Trending videos
- library_*.php   : User library (history, saved, liked)
- search_search_query.php : Search endpoint

All non-auth endpoint files are currently stubs that return example JSON.
You can fill in real database logic inside each file.
