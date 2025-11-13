---
title: Github
---

# deploy key
* https://github.com/USER/REPO/settings/keys/new
* make a new Host in ~/.ssh/config
```
  Host github-<repo>
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519_<repo>
  IdentitiesOnly yes
```
* `git clone git@github-<repo>:<owner>/<repo>.git`

# search your own gists
```
user:@me sphere
```

# clone private repo 1 level deep, master branch
```bash
gh auth login # choose: 'preferred protocol for Git operations? HTTPS'
git clone --depth 1 --branch master https://github.com/companje/PRIVATE-REPO.git
```

# List Public repos (parse with jq)
```bash
#!/bin/bash

ORG_NAME="YOUR_ORGANISATION"
TOKEN="ghp_......"

REPO_LIST=$(curl -s -H "Authorization: token $TOKEN" \
    "https://api.github.com/orgs/$ORG_NAME/repos?type=Public&per_page=100" \
    | jq -r '.[].name') 

for REPO_NAME in $REPO_LIST; do
    echo $REPO_NAME
done
```

# Backup organisation repositories
```bash
#!/bin/bash

ORG_NAME="YOUR_ORGANISATION"
TOKEN="ghp_......"
BACKUP_DIR="./repos"

# get repo list for organisation (max 100)
REPO_LIST=$(curl -s -H "Authorization: token $TOKEN" "https://api.github.com/orgs/$ORG_NAME/repos?type=all&per_page=100" | grep -Eo '"name": "[^"]+"' | awk '{print $2}' | tr -d '"')

# backup each repo
for REPO_NAME in $REPO_LIST; do
    echo "Cloning repository: $REPO_NAME"
    git clone "https://github.com/$ORG_NAME/$REPO_NAME.git" "$BACKUP_DIR/$REPO_NAME"
done
```

# Remove organisation repositories 
```bash
#!/bin/bash

TOKEN="ghp_...."
ORG_NAME="YOUR_ORGANISATION"
REPO_NAMES=(\
	"repo1" \
	"repo2" \
	"repo3")

# delete each repos
for REPO_NAME in "${REPO_NAMES[@]}"; do
    echo "Verwijderen van repository: $REPO_NAME"
    curl -X DELETE -H "Authorization: token $TOKEN" "https://api.github.com/repos/$ORG_NAME/$REPO_NAME"
done
```

# when using two-factor authentication 
you need an personal access token to use git on the cmd line:
https://github.com/settings/tokens

# use image in markdown
```
![](doc/images/my-image.jpg)
```

# github flavored markdown formatting
* https://help.github.com/articles/github-flavored-markdown/
* https://guides.github.com/features/mastering-markdown/

# tips 
* Updated in the last three days: updated:>2015-06-19.

# issues 
* https://help.github.com/articles/searching-issues/

# Managing GitHub projects
* https://www.lullabot.com/articles/managing-projects-with-github
* https://waffle.io
* https://robinpowered.com/blog/best-practice-system-for-organizing-and-tagging-github-issues/
* http://huboard.com
* http://sweepboard
