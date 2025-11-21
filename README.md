PEI WordPress Project

This repository contains the PEI WordPress site. Sensitive files and volatile runtime data are excluded from version control using the provided .gitignore.

Included
- Custom theme: wp-content/themes/pei/
- WordPress core files (you may choose to ignore core in the future and manage via deploys)
- Project metadata: .gitignore, .gitattributes, this README.md

Excluded (for safety)
- Secrets and environment files: wp-config.php, .env*, .htaccess, etc.
- Runtime data: wp-content/uploads/, caches, backups, logs
- Third-party plugins and themes (all plugins and themes are ignored except the pei theme)

First-time setup
1) Initialize Git in this folder, add a remote, and push the initial commit.
2) Example commands (run in this directory):
   - git remote add origin <YOUR_REMOTE_URL>
   - git push -u origin main

Daily workflow
- Make changes, then run: git add -A, git commit -m "Describe your change", git push

Restoring secrets on a new environment
- Copy wp-config.php from a secure location or recreate from wp-config-sample.php with correct DB credentials and salts.
- Recreate any environment files (.env) if you use them.
- Uploads are not versioned; restore from backups if needed.

Line endings
- .gitattributes normalizes text files to LF in the repo and keeps Windows scripts as CRLF to avoid cross-platform issues.

Adjusting ignore rules
- To track a specific plugin or theme, remove it from the ignore patterns in .gitignore.
- To version certain uploads (generally not recommended), add exceptions (for example: !wp-content/uploads/some-folder/).

Safe-by-default: No secrets or user uploads are pushed to Git.