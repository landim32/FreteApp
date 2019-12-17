using System;
using System.Collections.Generic;
using System.Linq;
using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Model;
using Emagine.Base.Utils;
using Foundation;
using ImageButton.iOS;
using UIKit;
using Xfx;

namespace Frete.iOS
{
    [Register("AppDelegate")]
    public partial class AppDelegate : global::Xamarin.Forms.Platform.iOS.FormsApplicationDelegate
    {
        public override bool FinishedLaunching(UIApplication app, NSDictionary options)
        {
            Plugin.Iconize.Iconize.With(new Plugin.Iconize.Fonts.FontAwesomeModule());

            XfxControls.Init();
            global::Xamarin.Forms.Forms.Init();

            Xamarin.FormsMaps.Init();
            ImageButtonRenderer.Init();
            FormsPlugin.Iconize.iOS.IconControls.Init();


            Plugin.Iconize.Iconize.With(new Plugin.Iconize.Fonts.FontAwesomeModule());

            GlobalUtils.setAplicacaoAtual(AplicacaoEnum.APLICACAO03);
            //Acr.UserDialogs.Init();

            LoadApplication(new App());

            return base.FinishedLaunching(app, options);
        }
    }
}
