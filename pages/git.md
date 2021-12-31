---
title: Git
---

# github cli
```bash
brew install gh
gh auth login
gh repo clone USER/REPO
```

# git optimized for github
https://hub.github.com

# Symbolic link to github application
```
ln -s  /Applications/GitHub\ Desktop.app/Contents/MacOS/GitHub\ Desktop  ~/bin/github
```

# undo last commit
[[http://stackoverflow.com/questions/927358/how-do-you-undo-the-last-commit|stackoverflow]]
  git reset HEAD~   

# clone with dept 1
   git clone --depth 1 git@github.com:Globe4D/Globe4D.git

# delete a file from git history 
(for example when you already deleted it but github keeps complaining about LFS large files)
```bash 
git filter-branch --tree-filter 'rm bin/data/images/BalloonChair-Field.psd' HEAD
# better working solution:
git filter-branch --index-filter 'git rm -r --cached --ignore-unmatch ./MarsGlobeWithTitles.zip' HEAD
```

# github software errors on windows
```
WARNING: git command could not be found. Please create an alias or add it to your PATH.
WARNING: Could not find ssh-agent
```

# Github Compare - compare two branches
* https://github.com/USER/REPO/compare

# Documentation
* https://www.gitbook.com/@companje/dashboard

# Syncing a fork
https://help.github.com/articles/syncing-a-fork/
  git remote -v  #List the current configured remote repository for your fork.
  git fetch upstream

#  Splitting a subfolder out into a new repository
* https://help.github.com/articles/splitting-a-subfolder-out-into-a-new-repository/

#  Remove sensitive data * https://help.github.com/articles/remove-sensitive-data/

# fatal: unable to access 'C:\Users\User/.gitconfig': Permission denied
the HOME env variable might be wrong. Try 
```set HOME```
and fix it if needed by setting it to the right value or by removing it:
```bashset HOME=c:\Users\YOUR_USER_NAME
rem OR: set HOME=
```

# flow for switching branches, removing untracked files, updating submodules
Warning: this will remove ALL untracked files and directories!!
```
git checkout master
git clean -xfdf     # be careful with this!
git checkout OtherBranchWithSubmodules
git submodule update --init --recursive
```


# remove all untracked files and folders including everything in .gitignore
```git clean -xfdf```

# create alias for clone recursive
in ~/.gitconfig
```
[alias]
        clr = clone --recursive
```

# graphical interface for git
[[http://www.sourcetreeapp.com/download/|sourcetree]]

# set remote url
```git remote set-url origin "git@github.com:USER/REPO.git"```

# info about remote url
```git remote -v```

# How to commit my current changes to a different branch in git
```
git stash
git checkout other-branch
git stash pop
```

# code completion for git
```bashsudo port install git-core git-extras```

# undo 'git add' before commit
```
git reset FILE
```

# create a new branch
```
git checkout -b Demo
```

# een branch pushen die remote nog niet bestaat
```
git push --all
```

# move subdir to separate git repo
http://stackoverflow.com/questions/359424/detach-subdirectory-into-separate-git-repository

# clone specific branch recursively
```bash
git clone --recursive -b BRANCHNAME git@github.com:..../.....git
```

# compare two commits
```
git diff HEAD HEAD~
git diff HEAD HEAD~~
```

# show all branches (local and remote)
```bash
git branch -a
```

# show remote branches 
```bash
git branch -r
```

# clone a specific branch
```bash
git clone -b experimental git@github.com:companje/ofxArcText.git
```

# existing repo
```bash
cd existing_git_repo
git remote add origin https://github.com/Doodle3D/Doodle3D.git
git push -u origin master
```

# change url of remote origin
```bashgit config remote.origin.url git@github.com:USER/PROJECT.git```

# open git config in editor
```bashgit config -e```

# git remote update
a git remote update followed by a ''git diff'' shows the changes you will get for a ''git pull''

# git pull
is a combination of ''git fetch'' and ''git merge''

# force a push
```bash
git push -f origin RicksVersie
```

# show branches with more information
```bash
git branch -av
```

# show listing of commits
```bash
git reflog
```

# move a branch
```bash
git branch -m master RicksVersie
git branch -m PetersVersie master
```

# delete a local branch
```bashgit branch -D crazy-idea```

# rename a local branch
```bashgit branch -M oldname newname```

# git with colors
```bash
git config --global color.ui true
```

# rollback /revert to last commit
not tested yet. [[http://stackoverflow.com/questions/4407232/git-rollback-to-a-previous-commit|more info]]
```
git reset --hard HEAD
```

# merge a branch into another branch
```bash
git checkout experimental
git merge master
```

# merge specific files/folder from another git branch
```bash
#You are in the branch you want to merge to
git checkout <branch_you_want_to_merge_from> <file_paths...>
```
[[http://jasonrudolph.com/blog/2009/02/25/git-tip-how-to-merge-specific-files-from-another-branch/|read more]]

# git (on github) keeps asking for password when using pull or push
You might have cloned the project over https. Check if this is the case using
```bash
git config -l
```
now change the remote origin to:
```bash
git config remote.origin.url git@github.com:USER/PROJECT.git
```

# verwijder de 'Leiden' branch op de remote
```bash
git push origin :Leiden
```

# verwijder een folder uit de cache
(niet uit je directory structuur)
```bash
git rm -r --cached libs/
```

# git push origin NAME
als je een nieuwe branch hebt gemaakt op een andere computer dan moet je die branch nog handmatig pushen naar remote met:
```bash
git push origin NAME
```

# If you are just after tracking someone else's project, this get you started quickly
```bash
git clone url = svn checkout url
git pull = svn update
```

# git clone depth
```bash
git clone git://github.com/openframeworks/openFrameworks.git –depth 1
```

# GIT gui programs
By default a couple of GUI programs are installed with git, for browsing through history  and committing etc.
```bash
gitk
git gui
```
you might first need to run ''sudo apt-get  install gitk''

# OSX git GUI's
* gitx
* http://brotherbard.com/blog/2010/03/experimental-gitx-fork/

# open git config editor
```bashgit config -e```
this opens the file .git/config
if this not works because of an error in .git/config just open the file with nano:
```bashnano .git/config```

# In je repository kun je zo iets pushen naar de server (bijv. github)
```bash
cd addons_rick/
git add ofxArcBall/
git commit -am "added ofxArcBall"
git push origin master
```

# checkout
to go back to a revision:
```bash
git checkout revisionid
```

to bring the repository back to it's most recent state
```bash
git checkout master
```

# Set up git
```bash
  git config --global user.name "Your Name"
  git config --global user.email your@emailaddress.com
```

# Get started with a newly created github repository
```bash
  mkdir PROJECTNAME
  cd PROJECTNAME
  git init
  touch README
  git add README
  git commit -m 'first commit'
  git remote add origin git@github.com:YOURUSERNAME/PROJECTNAME.git
  git push -u origin master
```

# creating new remote git repository
```bash
git init
or
git --bare init
```

# Permission denied (publickey)

You might be cloning a non-anonymous repository from github. If you just started using github (on your machine) github first needs to trust you. You can add the contents of ~/.ssh/id_rsa.pub to the SSH public key list on github at your account settings. If you don't want that just clone the repository through https or readonly.

Some very usefull info about ssh keys etc: http://help.github.com/ssh-issues/

# Create id_rsa files with ssh-keygen
just run ''ssh-keygen''

# Adding your public key to the server to remember your git user's password
```bash
scp ~/.ssh/id_rsa.pub user@remote.example.com:/tmp/id_rsa.pub
mkdir ~/.ssh
chmod 700 ~/.ssh
cat /tmp/id_rsa.pub >> ~/.ssh/authorized_keys
```

# interesting article
A successful Git branching model » nvie.com
http://nvie.com/posts/a-successful-git-branching-model/

# uitchecken van git project op onze server
hiervoor hebben we rick aan de 'git' groep toegevoegd met '''usermod -a -G git rick'''. Harmen deed ook nog iets met '''chgrp'''
```bash
git clone rick@git.giplt.nl:/home/giplt/git/datamining
```

# Nieuw git project opzetten op onze server
```bash
ssh username@giplt.nl
cd git
mkdir newproject
cd newproject/
git --bare init
```

# automatically pull from master
if you get this message:
```bash
If you often merge with the same branch, you may want to
configure the following variables in your configuration
file:

    branch.master.remote = <nickname>
    branch.master.merge = <remote-ref>
    remote.<nickname>.url = <url>
    remote.<nickname>.fetch = <refspec>
```

open the .git/config file (ie. by ''git config -e'') and set the branch.master.remote to ''origin'' and the branch.master.merge to ''refs/heads/master''

```bash
[core]
        repositoryformatversion = 0
        filemode = true
        bare = false
        logallrefupdates = true
        ignorecase = true

[remote "origin"]
        url = rick@companje.nl:/home/rick/git/Globe4D-base
        #fetch = +refs/heads/*:refs/remotes/origin/*
        fetch = refs/heads/master
```
# Eenmalig git configureren voor multi user repositories op de server
(for the record, want is nu al gedaan)
Dit zorgt er voor dat bij een commit de gepushde files als group niet de userid krijgen van degene die een bestand heeft aangemaakt, maar de gedeelde group (bij ons 'git') waarbinnen iedereen schrijfrechten heeft.

```bash
sudo chmod -R g+ws *
sudo chgrp -R mygroup *

git repo-config core.sharedRepository true
```

# remove origin from remote
```bash
git remote rm origin
```

# git add
first do a ''git --bare init'' on the server
```bash
git remote add origin {url}
```

# change editor for git
```bash
git config --global core.editor "nano"
```

# in case of this error:
```
error: src refspec master does not match any.
error: failed to push some refs to ..
```
you're commit might went wrong or you just forgot to commit:
```bash
git commit -m 'first commit'
```

# submodules in a project
see [[http://stackoverflow.com/questions/4161022/git-how-to-track-untracked-content/4162672#4162672|this page]]

add submodule to a project (this means to clone another repo into a subfolder of your repo)
```bash
git submodule add git://github.com/test/test.git subfolder/test
```

```bash
git submodule update --init
```

remove a submodule
```bash
git rm --cached folder/submodule
```

you can also clone your repo recursively, that way also submodules are cloned.

```bash
git clone --recursive http://server/repo.git
```

# pull git submodules
see the [[http://book.git-scm.com/5_submodules.html|git book]]
```bash
git submodule init
git submodule update
```

nog niet getest:
```bash
git submodule update --init --recursive
```

# remove traces of wrongly removed submodules
```git config -e```
```rm -rf .git/modules/*```

# fatal: Needed a single revision 
cloning probably resulted in an empty folder. Delete the folder and try to clone again.

# useful git commands
http://xinyustudio.wordpress.com/2011/12/11/a-brief-list-of-git-commands/

# git list files
```bashgit ls-files```

# git cheat sheet
http://help.github.com/git-cheat-sheets/
