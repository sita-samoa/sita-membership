# docker aliases
## https://gist.github.com/ainsofs/ba947741b230606be5d2f4aad6faf7bf
## common
alias dc='docker compose'
alias dup='docker compose up -d --remove-orphans'
alias dstop='docker compose stop'
alias drm='docker compose rm'

## helpers
alias dupp='docker compose up -d && docker compose exec php bash' # Start project and open php container.
alias dupl='docker compose up -d; docker compose logs -f php' # Start project and show php logs
alias dkill='docker kill $(docker ps -q)' # Kill all docker containers.
alias dbdb='docker compose stop mariadb && docker compose rm -f mariadb && docker compose up -d && docker compose logs -f mariadb' # Reload database.
alias dbphp='docker compose stop php && docker compose rm -f php && docker compose up -d' # Reload php container.

## logs
alias dl='docker compose logs -f' # eg dl nginx for nginx logs, dl php for php logs.

## container access
alias dphp='docker compose exec php bash'
alias dnode='docker compose exec node bash'
alias dq="docker compose exec quasar bash"

## database
alias dbdb2='docker compose stop mariadb2 && docker compose rm -f mariadb2 && docker compose up -d && docker compose logs -f mariadb2' # Reload db2.
alias dbdb3='docker compose stop mariadb3 && docker compose rm -f mariadb3 && docker compose up -d && docker compose logs -f mariadb3' # Reload db3.

# ddev aliases
alias vcomposer="ddev composer"
alias vnode="ddev node"
alias vdrush="ddev drush"
alias vphp="ddev php"
alias vstart="ddev start"
alias vstop="ddev stop"
alias vssh="ddev ssh"
alias vconfig="ddev config"
alias vpoweroff="ddev poweroff"
alias vcupdate="vi .ddev/config.yaml"
# alias vcupdate="nano .ddev/config.yaml"

#docker4drupal aliases
alias mcomposer="make composer"
alias martisan="make artisan"
alias mlogs="make logs"
alias mshell="make shell"
# alias mnode="make node"
# alias mphp="make php"

# git aliases
## https://medium.com/@barend.bootha/git-fu-what-c0753094a6c6
alias gwip="git add . && gc -m 'wip' --no-verify"
alias g="git"
alias gc="git commit"
alias gs="git status"
alias gd="git diff"
# alias gl="git log"
alias gan="git add"
alias ga="git add -p"
alias gch="git checkout"
alias gchb="git checkout -b"
alias gb="git branch"
alias gr="git reset"
alias gp="git push --follow-tags"
alias gt="git tag"
alias gpl="git pull"
alias gclean="find . -type f -name '*.orig' -exec rm -r {} +"
alias gbegone="git clean -f -d"

function parse_git_branch {
  git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/ \1/' -e "s/ //"
}

function gcm {
  git commit -m "$(parse_git_branch) $@"
}

alias gl='git log --pretty=oneline --abbrev-commit'
alias gl2='git log $(git describe --tags --abbrev=0)..HEAD --pretty=format:"- %s"'
alias gl3='git log --format="%C(auto) %h %s"'
alias gc2='git commit -m'
# alias gac='git add . ; git commit -m'
# alias gpo="git push origin"
alias gfo="git fetch origin"
alias gfp="git fetch --prune"

# permissions
alias fix_owners="sudo chown -R 1000:1000 ."
alias fix_permissions="sudo chmod -R 777 ."

# shortcuts
alias c="clear"

# bash aliases
# alias bashrc='nano ~/.bash_aliases'
alias bashrc='vim ~/.bash_aliases'
alias bashs='source ~/.bashrc'