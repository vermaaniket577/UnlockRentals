import UIKit

@main
class AppDelegate: UIResponder, UIApplicationDelegate {

    var window: UIWindow?

    func application(_ application: UIApplication,
                     didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?) -> Bool {

        window = UIWindow(frame: UIScreen.main.bounds)
        window?.rootViewController = ViewController()
        window?.makeKeyAndVisible()

        // Set status bar style
        if #available(iOS 13.0, *) {
            // Handled by ViewController's preferredStatusBarStyle
        } else {
            UIApplication.shared.statusBarStyle = .lightContent
        }

        return true
    }

    // MARK: - Deep Links / Universal Links

    func application(_ application: UIApplication,
                     continue userActivity: NSUserActivity,
                     restorationHandler: @escaping ([UIUserActivityRestoring]?) -> Void) -> Bool {
        guard userActivity.activityType == NSUserActivityTypeBrowsingWeb,
              let url = userActivity.webpageURL else { return false }

        // Load the URL in our WebView
        if let viewController = window?.rootViewController as? ViewController {
            // The ViewController will handle loading the URL
            NotificationCenter.default.post(name: Notification.Name("LoadURL"), object: url)
        }
        return true
    }

    func application(_ app: UIApplication,
                     open url: URL,
                     options: [UIApplication.OpenURLOptionsKey: Any] = [:]) -> Bool {
        NotificationCenter.default.post(name: Notification.Name("LoadURL"), object: url)
        return true
    }
}
