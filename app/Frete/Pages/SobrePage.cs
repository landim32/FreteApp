using System;

using Xamarin.Forms;

namespace Frete.Pages
{
    public class SobrePage : ContentPage
    {
        public SobrePage()
        {
            Title = "Sobre nós";
            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout()
                {
                    Padding = new Thickness(10, 20),
                    Spacing = 5,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Orientation = StackOrientation.Vertical,
                    Children = {
                        new Image{
                            Source = "logo.png",
                            HeightRequest = 120,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Center
                        },
                        new Label(){
                            Text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam in consectetur quam. Donec a maximus ex, et commodo diam. Aliquam sit amet tincidunt sem, non sollicitudin turpis. Suspendisse ullamcorper volutpat blandit. In at enim metus. Phasellus turpis tortor, ullamcorper et auctor vel, lacinia tincidunt ex. Nunc hendrerit porttitor tellus ac volutpat. Mauris in vehicula sapien, et pretium odio. Sed eget feugiat turpis, id congue elit. Pellentesque porta sagittis urna, ut interdum tortor molestie ut. Mauris vehicula euismod magna, vel dignissim urna tincidunt nec." +
                                    "Nam eleifend dapibus leo nec venenatis. In rhoncus diam odio, quis ultricies sem commodo quis. Nunc porttitor facilisis metus et accumsan. Vivamus congue vitae urna a ornare. Maecenas placerat nulla ex, vitae faucibus arcu eleifend sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi ac ullamcorper augue. Aliquam erat volutpat. Integer ut congue odio."+
                                    "Aenean id sollicitudin lectus. Nunc in lacinia ipsum. Sed ut consequat nulla. Vivamus diam dolor, condimentum sed semper nec, porta et orci. Nullam nulla arcu, ullamcorper id lorem a, lobortis dapibus justo. Nullam luctus justo vel ex vehicula faucibus. Quisque mollis, quam vel suscipit ornare, lacus est aliquam mauris, vel vulputate odio sapien vitae felis. Vestibulum quis nulla non erat tincidunt sodales in non ligula. Suspendisse vel tortor id turpis aliquam mattis. Nulla volutpat, ipsum non vulputate ullamcorper, ex massa vehicula mauris, rutrum porttitor arcu ipsum ut tellus. Sed eget justo a metus faucibus fringilla id eu metus. Nunc sed facilisis neque. Integer blandit diam dui, eu mollis elit volutpat id."
                        }
                    }
                }
            };
        }
    }
}

