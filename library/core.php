<?php
/* Core PHP file for the Wireless PHP Framework */

//All PATH defines are declared here in a block-based heirarchy for easy visualization. All other defines should come from a configuration file.
define("PATH_OMEGA_ROOT", DIRECTORY_SEPARATOR); {
	define("PATH_MEGA_ROOT", PATH_OMEGA_ROOT . "opt"); {
		define("PATH_ROOT", PATH_MEGA_ROOT . DIRECTORY_SEPARATOR . "larsson"); {
			define("PATH_CONF", PATH_ROOT . DIRECTORY_SEPARATOR . "conf"); {
				define("PATH_CONF_CORE", PATH_CONF . DIRECTORY_SEPARATOR . "core"); {
				}
				define("PATH_CONF_DATABASE", PATH_CONF . DIRECTORY_SEPARATOR . "database"); {
					define("PATH_CONF_DATABASE_STATEMENTS", PATH_CONF_DATABASE . DIRECTORY_SEPARATOR . "statements"); {
					}
				}
			}
			define("PATH_LIBRARY", PATH_ROOT . DIRECTORY_SEPARATOR . "library"); {
				define("PATH_LIBRARY_CORE", PATH_LIBRARY . DIRECTORY_SEPARATOR . "core"); {
				}
				define("PATH_LIBRARY_DATABASE", PATH_LIBRARY . DIRECTORY_SEPARATOR . "database"); {
				}
			}
		}
	}
}

//Pull in our custom AutoLoader class.
require_once(PATH_LIBRARY_CORE . DIRECTORY_SEPARATOR . "AutoLoader.class.php");

//Register our AutoLoader class.
spl_autoload_register("Larsson\Library\Core\AutoLoader::load", true, true);

//Parse all of the configuration files.
$conf = parse_ini_file(PATH_CONF_CORE . DIRECTORY_SEPARATOR . "conf.ini", true);

//Set all of our defines that were loaded from the configuration file.
foreach ($conf["constants"] as $define => $value) {
	define(strtoupper($define), $value);
}

//Initialize the AutoLoader to handle our naming conventions.
Larsson\Library\Core\AutoLoader::__initialize($conf["autoload_class_types"]);
?>
