package {
	import com.longtailvideo.jwplayer.events.*;
	import com.longtailvideo.jwplayer.plugins.*;
	import com.longtailvideo.jwplayer.player.*;
	import com.longtailvideo.jwplayer.view.components.*;
	import com.longtailvideo.jwplayer.controller.*;
	import com.longtailvideo.jwplayer.utils.*;
    import flash.display.*;
    import flash.events.*;
    import flash.ui.*;
    import flash.utils.*;
	
	public class Slowmo extends Sprite implements IPlugin6 {
		//player variables
		private var api:IPlayer;
		private var player:IPlayer;
		private var pluginConfig:PluginConfig;
		
		//plugin variables
        private var isOn:Boolean = false;
        private var timer:Timer;
        private var theProgress:Number;
        private var delay:Number;
        private var isPlaying:Boolean;
        private var theCount:uint = 0;
		
		//controlbar icon
		[Embed(source="controlbar.png")]
		private const ControlbarIcon:Class;
		
		//dock icon
		[Embed(source="icon.png")]
		private const Icon:Class;
		
		/** Reference to embed icon **/
		private var skinIcon:DisplayObject;
		
		/** Reference to the dock button **/
		private var dockButton:DockButton;
		
		/** Reference to the clip on stage. **/
		private var controlIcon:DisplayObject;
		
		/** Let the player know what the name of your plugin is. **/
		public function get id():String { return "slowmo"; }
		
		/** Let the player know what version of the player you are targeting. **/
		public function get target():String {
			return "6.0";
		}
		
		/** List with configuration settings. **/
		public var defaultConfig:Object = {
			delay:'50'
		};

		/** Constructor **/
		public function Slowmo() {
		}
		
		/* Called by the player after the plugin has been created. */
		public function initPlugin(player:IPlayer, config:PluginConfig):void {
			api = player;
			player = player;
			this.player = player;
				skinIcon = player.skin.getSkinElement("Slowmo ", "dockIcon");
				if (skinIcon == null) 
				{
					skinIcon = new Icon();
				}
				dockButton = player.controls.dock.addButton(skinIcon, "Slowmo", dockHandler) as DockButton;
			if(config['delay'] == null){
			delay = 50;
			} else {
			delay = config['delay'];
			}
		}	
		
		public function makeSlowMo(event:TimerEvent):void
        {
            if (theProgress == 0)
            {
                player.play();
                isPlaying = true;
				player.mute(true);
            }
            else if (theProgress == 1)
            {
                player.pause();
                isPlaying = false;
            }
            else if (theProgress == 2)
            {
                theProgress = -1;
            }
            var theLocation = theProgress + 1;
            theProgress = theLocation;
        }
		
		public function slowmo():void
        {
            if (player.play() == true || player.play() == false)
            {
                if (isOn)
                {
                    timer.stop();
                    isOn = false;
                    if (player.state == "PLAYING")
                    {
						player.config["icons"] = true;
                        player.play();
						player.mute(false);
                    }
                }
                else
                {
                    player.config["icons"] = false;
					player.addEventListener(MediaEvent.JWPLAYER_MEDIA_COMPLETE,mediaStop);
                    if (theCount == 0)
                    {
						timer = new Timer(delay);
                        timer.addEventListener(TimerEvent.TIMER, makeSlowMo);
                    }
                    timer.start();
                    theProgress = 0;
                    isOn = true;
                    var theLocation2 = theCount + 1;
                    theCount = theLocation2;
                }
            }
        }
		
		function mediaStop(evt:MediaEvent) { 
			player.config["icons"] = true;
			player.mute(false);
			timer.stop();
			isOn = false;
            if (player.state == "COMPLETED")
            {
            player.stop();
			}
		}
		
		/** Dock icon is clicked **/
		private function dockHandler(evt:MouseEvent):void 
		{	
			slowmo();
		}
		
		/** Controlbar button icon is clicked **/
		private function controlHandler(evt:MouseEvent):void 
		{
			slowmo();
		}
		
		/* When the player resizes itself, it sets the x/y coordinates of all components and plugins. */		
		public function resize(wid:Number, hei:Number):void {
		}
	}
}