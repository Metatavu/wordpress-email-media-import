/*global module:false*/

var util = require('util');
var fs = require("fs");
var config = require("./grunt-config.json");

module.exports = function(grunt) {
  require("load-grunt-tasks")(grunt);

  var installPlugins = [];
  var activatePlugins = [];
  var linkPluginsConfig = [];
  
  var plugins = Object.keys(config.wordpress.plugins);
  for (var i = 0, l = plugins.length; i < l; i++) {
    var plugin = plugins[i];
    var settings = config.wordpress.plugins[plugin];
    
    if (settings.file) {
      linkPluginsConfig.push({
        overwrite: true,
        src: settings.file,
        dest: util.format("%s/wp-content/plugins/%s", config.wordpress.path, plugin)
      });
    } else if (settings.url) {
      installPlugins.push(settings.url);
    } else {
      installPlugins.push(plugin);
    }
    
    activatePlugins.push(plugin);
  };
  
  grunt.initConfig({
    "mustache_render": {
      "database-init": {
        files : [{
          data: {
            "DATABASE": config.wordpress.database.name,
            "USER": config.wordpress.database.user,
            "PASSWORD": config.wordpress.database.password,
            "HOST": config.wordpress.database.host||"localhost"
          },
          template: "templates/init-database.sql.mustache",
          dest: "templates/init-database.sql"
        }]
      },
      "database-drop": {
        files : [{
          data: {
            "DATABASE": config.wordpress.database.name
          },
          template: "templates/drop-database.sql.mustache",
          dest: "templates/drop-database.sql"
        }]
      }
    },
    "mysqlrunfile": {
      options: {
        connection: {
          host: config.mysql.host,
          user: config.mysql.user,  
          password: config.mysql.password,
          multipleStatements: true
        }
      },
      "database-init": {
        src: ["templates/init-database.sql"]
      },
      "database-drop": {
        src: ["templates/drop-database.sql"]
      }
    },
    "wp-cli": {
      "download": {
        "path": config.wordpress.path,
        "command": "core",
        "subcommand": "download",
        "options": {"locale": "fi"}
      },
      "config": {
        "path": config.wordpress.path,
        "command": "core",
        "subcommand": "config",
        "options": {
          "dbname": config.wordpress.database.name,
          "dbuser": config.wordpress.database.user,
          "dbpass": config.wordpress.database.password,
          "locale": "fi"
        }
      },
      "install": {
        "path": config.wordpress.path,
        "command": "core",
        "subcommand": "install",
        "options": {
          "url": config.wordpress.site.url,
          "title": config.wordpress.site.title,
          "admin_user": config.wordpress.site.adminUser,
          "admin_password": config.wordpress.site.adminPassword,
          "admin_email": config.wordpress.site.adminEmail,
          "skip-email": true
        }
      },
      "install-plugins": {
        "path": config.wordpress.path,
        "command": "plugin",
        "subcommand": "install",
        "arguments": installPlugins.join(' ')
      },
      "activate-plugins": {
        "path": config.wordpress.path,
        "command": "plugin",
        "subcommand": "activate",
        "arguments": activatePlugins.join(' ')
      },
      "update-languages": {
        "path": config.wordpress.path,
        "command": "core",
        "subcommand": "language update"
      },
      "update-plugins": {
        "path": config.wordpress.path,
        "command": "plugin",
        "subcommand": "update",
        "options": {
          "all": true
        }
      }
    },
    "shell": {
      "wordpress-languages-writable": {
        "command": "chmod a+w languages",
        "options": {
          "execOptions": {
            "cwd": config.wordpress.path + "/wp-content"
          }
        }
      },
      "wordpress-permalinks": {
        "command": "wp option update permalink_structure /archives/%post_id% --path=wp"
      },
      "wordpress-email-options": {
        "command": "wp option update email_media_import '{ \"mailgunKey\": \"key\" }' --format=json --path=wp"
      },
      "start-wordpress-server": {
        "command": util.format("php -S %s -t wp", config.wordpress.site.url)
      }
    },
    "symlink": {
      "wordpress-plugins": {
        "files": linkPluginsConfig
      }
    },
    'bgShell': {
      "start-wordpress-server-background": {
        cmd: "php -d xdebug.remote_port=2345 -e -S " + config.wordpress.site.url + " & echo $! > /tmp/wordpress-server.pid",
        bg: true,
        execOpts: {
          cwd: config.wordpress.path
        }
      },
      "kill-wordpress-server-background": {
        bg: true,
        cmd: "PID=`cat /tmp/wordpress-server.pid` && kill $PID"
      }
    },
    "wait": {
      "2s": {
        "options": {
          "delay": 2000
        }
      }
    },
    "clean": {
      "uninstall-wordpress": [ config.wordpress.path ]
    }
  });
  
  /// TODO: Change shell:wordpress-permalinks to wp-cli
  
  grunt.registerTask("create-database", ["mustache_render:database-init", "mysqlrunfile:database-init"]);
  grunt.registerTask("install-wordpress", ["wp-cli:download", "wp-cli:config", "wp-cli:install", "shell:wordpress-languages-writable", "symlink:wordpress-plugins", "wp-cli:install-plugins", "wp-cli:activate-plugins", "wp-cli:update-languages", "shell:wordpress-permalinks", "shell:wordpress-email-options"]);
  grunt.registerTask("start-server", ["bgShell:start-wordpress-server-background", "wait:2s"]);
  grunt.registerTask("start-blocking-server", ["shell:start-wordpress-server"]);
  grunt.registerTask("stop-server", ["bgShell:kill-wordpress-server-background"]);
  grunt.registerTask("uninstall-wordpress", ["clean:uninstall-wordpress"]);
  grunt.registerTask("drop-database", ["mustache_render:database-drop", "mysqlrunfile:database-drop"]);
  
};