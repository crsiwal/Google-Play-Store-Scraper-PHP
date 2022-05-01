## Google Play Store Scraper PHP

PHP Script to scrape application data from the Google Play store.

Using this script you can download the applications following data in the CSV format.

- Owner Name
- Application Name
- Number of total installs
- Last Update Date and Time
- Developer Email Id
- Category Name
- Rating Count
- Application APK Size
- Application Current Version
- Minimum Android Version Required

### Process

- Get the Applications Playstore URL and save these url in the `urls.csv` file. Sample file is available [here](https://github.com/crsiwal/Google-Play-Store-Scraper-PHP/blob/main/urls.csv)
- Then run the `Scraper.php` file in command line. Syntax: `php Scraper.php`

### Get Playstore Apps URL
To get the play store apps url you can run the following `Javascript` code  

```JavaScript
/** Function for save the files data to local disk **/
function download(filename, text) {
    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    pom.setAttribute('download', filename);
    if (document.createEvent) {
        var event = document.createEvent('MouseEvents');
        event.initEvent('click', true, true);
        pom.dispatchEvent(event);
    }
    else {
        pom.click();
    }
}

/** Get the Package name from console using following javascript **/
var apps = document.getElementsByClassName("poRVub");
var apps_url = "";
var apps_count = 0;
for (var i = 0; i < apps.length; i++) {
	let url = apps.item(i).href;
	if(url.indexOf("store/apps/details") !== -1){
		//package_name += url.split('?id=')[1] + ",";
		apps_url += url + "\n";
		apps_count++;
	}
}
console.log(apps_count);
download('urls.csv', apps_url);
```

- After download you should remove duplicate URL's from all apps url using `Microsoft Excel` remove duplicate tool.


## 💻 Contributing

Contributions are always welcome!

See [contribution guidelines](contributing.md) for ways to get started.

Please adhere to project's `code of conduct`.
## A supporter is worth a thousand followers.

If you have found anything useful and you want to support me, then Buy me a [Coffee](https://www.buymeacoffee.com/rsiwal) or [Chai](https://getmechai.vercel.app/link.html?vpa=rsiwal@upi&nm=Rahul+Siwal&amt=500)

[Buy Me a Coffee](https://www.buymeacoffee.com/rsiwal) or [Buy Me a Chai](https://getmechai.vercel.app/link.html?vpa=rsiwal@upi&nm=Rahul+Siwal&amt=500) is a modern support platform by people can express appreciation for someone work. It will take a minute of you to give a appreciation to me which will encourage me to write something new for the web and it may be your next profit business.

If you want to contribute feel free to do it at Buy me a [Coffee](https://www.buymeacoffee.com/rsiwal) or [Chai](https://getmechai.vercel.app/link.html?vpa=rsiwal@upi&nm=Rahul+Siwal&amt=500), and many thanks in advance.

For as we well know

var code = developer + caffeine

😁

ps. Jam doughnut are also a perfectly acceptable sign of appreciation 🍩

#### User From India Can support here
[![Support From India](https://i.ibb.co/Xkdj83y/image-2.png)](https://getmechai.vercel.app/link.html?vpa=rsiwal@upi&nm=Rahul+Siwal&amt=500)

#### User From Outside India Can support here
[![Support From Outside India](https://img.buymeacoffee.com/button-api/?text=Buy%20a%20coffee%20for%20me&emoji=&slug=rsiwal&button_colour=FF5F5F&font_colour=ffffff&font_family=Cookie&outline_colour=000000&coffee_colour=FFDD00)](https://www.buymeacoffee.com/rsiwal)

## 🤝 Connect with me

[![](https://img.shields.io/badge/Profile-Twitter-1DA1F2?style=flat&logo=twitter&color=336791&logoColor=white)](https://www.twitter.com/rahulsiwal)

[![](https://img.shields.io/badge/Profile-Linkedin-0A66C2?style=flat&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/rsiwal)

[![](https://img.shields.io/badge/Profile-Facebook-3b5998?style=flat&logo=facebook&logoColor=white)](https://www.facebook.com/rsiwal)

[![](https://img.shields.io/badge/Profile-Instagram-3f729b?style=flat&logo=instagram&logoColor=white)](https://www.instagram.com/rahulsiwal)

## Feedback

If you have any feedback, please reach out to me at rsiwal@yahoo.com


## License 

[![CC0](https://licensebuttons.net/p/zero/1.0/88x31.png)](https://creativecommons.org/publicdomain/zero/1.0/)

To the extent possible under law, [Rahul Siwal](https://github.com/crsiwal) has waived all copyright and related or neighboring rights to this work.