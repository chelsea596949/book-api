@echo off
cd /d C:\xampp\htdocs\books-api.worktrees\agents-fix-auth-token-expiration-bug
git add public\js\home.js
git commit -m "fix: add token expiration check to prevent stale auth state" -m "- Check if auth_token is not only present but also unexpired^
- Add isTokenValid() function to compare current time with exp timestamp^
- Add clearAuthData() helper to properly clean up all auth-related localStorage items^
- Modify logout handler to use clearAuthData()^
- Now token expiry automatically clears login state and hides admin panel^
^
Co-authored-by: Copilot <223556219+Copilot@users.noreply.github.com>"
