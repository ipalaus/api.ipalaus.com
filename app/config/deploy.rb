set :application, "Isern Palaus"
set :repository,  "."

set :deploy_to, "/var/www/ipalaus_com"

set :deploy_via, :copy
set :scm, :none

# do not copy these files, adjust to your needs
set :copy_exclude, [".git", ".gitignore", "Capfile", ".config"]

# SSH User to Deploy As
set :user, 'www-data'

set :use_sudo, false
set :keep_releases, 5

ssh_options[:forward_agent] = true
ssh_options[:keys] = "/var/lib/jenkins/.ssh/production"

# Hostnames of your App Servers
role :app, "app-01.ipalaus.com"

namespace :deploy do

  task :finalize_update do
    # This strips out some of the default rails
    # tasks that we don't need for PHP.
  end

  task :migrate do
    run "php #{latest_release}/artisan migrate"
  end

  # Optional- Reload PHP-FPM after the code is deployed
  task :restart, :roles => [:app] do
    run "/etc/init.d/php5-fpm reload"
    run "sudo /usr/bin/supervisorctl restart all"
  end

end

# leave only 5 releases
after "deploy", "deploy:migrate", "deploy:cleanup"
