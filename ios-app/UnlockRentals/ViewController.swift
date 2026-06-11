import UIKit
import WebKit

class ViewController: UIViewController, WKNavigationDelegate, WKUIDelegate {

    private var webView: WKWebView!
    private var refreshControl: UIRefreshControl!
    private var errorView: UIView!
    private var activityIndicator: UIActivityIndicatorView!

    private let productionURL = "https://unlockrentals.in"

    override func viewDidLoad() {
        super.viewDidLoad()
        view.backgroundColor = UIColor(red: 15/255, green: 23/255, blue: 42/255, alpha: 1) // #0f172a

        setupWebView()
        setupRefreshControl()
        setupErrorView()
        setupActivityIndicator()
        loadProductionURL()
    }

    // MARK: - Setup

    private func setupWebView() {
        let config = WKWebViewConfiguration()
        config.allowsInlineMediaPlayback = true
        config.mediaTypesRequiringUserActionForPlayback = []

        // Enable local storage
        config.preferences.javaScriptEnabled = true
        config.websiteDataStore = .default()

        webView = WKWebView(frame: view.bounds, configuration: config)
        webView.autoresizingMask = [.flexibleWidth, .flexibleHeight]
        webView.navigationDelegate = self
        webView.uiDelegate = self
        webView.allowsBackForwardNavigationGestures = true
        webView.scrollView.bounces = true

        // Custom User-Agent
        webView.evaluateJavaScript("navigator.userAgent") { [weak self] result, _ in
            if let defaultUA = result as? String {
                self?.webView.customUserAgent = "\(defaultUA) UnlockRentalsApp/1.0-iOS"
            }
        }

        // Safe area handling
        if #available(iOS 11.0, *) {
            webView.scrollView.contentInsetAdjustmentBehavior = .automatic
        }

        view.addSubview(webView)
    }

    private func setupRefreshControl() {
        refreshControl = UIRefreshControl()
        refreshControl.tintColor = UIColor(red: 37/255, green: 99/255, blue: 235/255, alpha: 1) // #2563EB
        refreshControl.addTarget(self, action: #selector(refreshWebView), for: .valueChanged)
        webView.scrollView.refreshControl = refreshControl
    }

    private func setupErrorView() {
        errorView = UIView(frame: view.bounds)
        errorView.autoresizingMask = [.flexibleWidth, .flexibleHeight]
        errorView.backgroundColor = UIColor(red: 15/255, green: 23/255, blue: 42/255, alpha: 1)
        errorView.isHidden = true

        // Icon
        let iconConfig = UIImage.SymbolConfiguration(pointSize: 60, weight: .light)
        let iconView = UIImageView(image: UIImage(systemName: "wifi.slash", withConfiguration: iconConfig))
        iconView.tintColor = UIColor(red: 96/255, green: 165/255, blue: 250/255, alpha: 1)
        iconView.translatesAutoresizingMaskIntoConstraints = false
        errorView.addSubview(iconView)

        // Title
        let titleLabel = UILabel()
        titleLabel.text = "No Internet Connection"
        titleLabel.textColor = .white
        titleLabel.font = .systemFont(ofSize: 24, weight: .bold)
        titleLabel.translatesAutoresizingMaskIntoConstraints = false
        errorView.addSubview(titleLabel)

        // Subtitle
        let subtitleLabel = UILabel()
        subtitleLabel.text = "Please check your connection and try again."
        subtitleLabel.textColor = UIColor(red: 156/255, green: 163/255, blue: 175/255, alpha: 1)
        subtitleLabel.font = .systemFont(ofSize: 16, weight: .regular)
        subtitleLabel.textAlignment = .center
        subtitleLabel.numberOfLines = 0
        subtitleLabel.translatesAutoresizingMaskIntoConstraints = false
        errorView.addSubview(subtitleLabel)

        // Retry Button
        let retryButton = UIButton(type: .system)
        retryButton.setTitle("Retry", for: .normal)
        retryButton.setTitleColor(.white, for: .normal)
        retryButton.titleLabel?.font = .systemFont(ofSize: 16, weight: .bold)
        retryButton.backgroundColor = UIColor(red: 37/255, green: 99/255, blue: 235/255, alpha: 1)
        retryButton.layer.cornerRadius = 12
        retryButton.translatesAutoresizingMaskIntoConstraints = false
        retryButton.addTarget(self, action: #selector(retryLoading), for: .touchUpInside)
        errorView.addSubview(retryButton)

        view.addSubview(errorView)

        NSLayoutConstraint.activate([
            iconView.centerXAnchor.constraint(equalTo: errorView.centerXAnchor),
            iconView.centerYAnchor.constraint(equalTo: errorView.centerYAnchor, constant: -80),

            titleLabel.topAnchor.constraint(equalTo: iconView.bottomAnchor, constant: 24),
            titleLabel.centerXAnchor.constraint(equalTo: errorView.centerXAnchor),

            subtitleLabel.topAnchor.constraint(equalTo: titleLabel.bottomAnchor, constant: 8),
            subtitleLabel.centerXAnchor.constraint(equalTo: errorView.centerXAnchor),
            subtitleLabel.leadingAnchor.constraint(greaterThanOrEqualTo: errorView.leadingAnchor, constant: 40),
            subtitleLabel.trailingAnchor.constraint(lessThanOrEqualTo: errorView.trailingAnchor, constant: -40),

            retryButton.topAnchor.constraint(equalTo: subtitleLabel.bottomAnchor, constant: 32),
            retryButton.centerXAnchor.constraint(equalTo: errorView.centerXAnchor),
            retryButton.widthAnchor.constraint(equalToConstant: 200),
            retryButton.heightAnchor.constraint(equalToConstant: 50)
        ])
    }

    private func setupActivityIndicator() {
        activityIndicator = UIActivityIndicatorView(style: .large)
        activityIndicator.color = UIColor(red: 37/255, green: 99/255, blue: 235/255, alpha: 1)
        activityIndicator.translatesAutoresizingMaskIntoConstraints = false
        view.addSubview(activityIndicator)

        NSLayoutConstraint.activate([
            activityIndicator.centerXAnchor.constraint(equalTo: view.centerXAnchor),
            activityIndicator.centerYAnchor.constraint(equalTo: view.centerYAnchor)
        ])
    }

    // MARK: - Loading

    private func loadProductionURL() {
        guard let url = URL(string: productionURL) else { return }

        if isNetworkAvailable() {
            errorView.isHidden = true
            webView.isHidden = false
            activityIndicator.startAnimating()
            webView.load(URLRequest(url: url))
        } else {
            showErrorPage()
        }
    }

    @objc private func refreshWebView() {
        webView.reload()
    }

    @objc private func retryLoading() {
        loadProductionURL()
    }

    // MARK: - Error Handling

    private func showErrorPage() {
        webView.isHidden = true
        errorView.isHidden = false
        activityIndicator.stopAnimating()
        refreshControl.endRefreshing()
    }

    private func isNetworkAvailable() -> Bool {
        // Simple reachability check via URLSession
        guard let url = URL(string: "https://www.apple.com/library/test/success.html") else { return false }
        var isAvailable = false
        let semaphore = DispatchSemaphore(value: 0)

        var request = URLRequest(url: url, timeoutInterval: 3)
        request.httpMethod = "HEAD"

        URLSession.shared.dataTask(with: request) { _, response, _ in
            if let httpResponse = response as? HTTPURLResponse, httpResponse.statusCode == 200 {
                isAvailable = true
            }
            semaphore.signal()
        }.resume()

        _ = semaphore.wait(timeout: .now() + 3)
        return isAvailable
    }

    // MARK: - WKNavigationDelegate

    func webView(_ webView: WKWebView, didStartProvisionalNavigation navigation: WKNavigation!) {
        if !refreshControl.isRefreshing {
            activityIndicator.startAnimating()
        }
    }

    func webView(_ webView: WKWebView, didFinish navigation: WKNavigation!) {
        activityIndicator.stopAnimating()
        refreshControl.endRefreshing()

        // Hide PWA install prompt and feedback trigger in the app
        let hideScript = """
            var style = document.createElement('style');
            style.innerHTML = '.pwa-install-prompt, .feedback-modal-trigger { display: none !important; }';
            document.head.appendChild(style);
        """
        webView.evaluateJavaScript(hideScript, completionHandler: nil)
    }

    func webView(_ webView: WKWebView, didFailProvisionalNavigation navigation: WKNavigation!, withError error: Error) {
        activityIndicator.stopAnimating()
        refreshControl.endRefreshing()
        showErrorPage()
    }

    func webView(_ webView: WKWebView,
                 decidePolicyFor navigationAction: WKNavigationAction,
                 decisionHandler: @escaping (WKNavigationActionPolicy) -> Void) {

        guard let url = navigationAction.request.url else {
            decisionHandler(.allow)
            return
        }

        let urlString = url.absoluteString
        let host = url.host ?? ""

        // Handle external links
        if urlString.hasPrefix("tel:") || urlString.hasPrefix("mailto:") ||
           urlString.hasPrefix("whatsapp://") || host.contains("wa.me") ||
           host.contains("play.google.com") || host.contains("apps.apple.com") ||
           host.contains("itunes.apple.com") {
            UIApplication.shared.open(url)
            decisionHandler(.cancel)
            return
        }

        // External websites — open in Safari
        if !host.contains("unlockrentals") && !host.isEmpty &&
           navigationAction.navigationType == .linkActivated {
            UIApplication.shared.open(url)
            decisionHandler(.cancel)
            return
        }

        decisionHandler(.allow)
    }

    // MARK: - WKUIDelegate (File Upload)

    func webView(_ webView: WKWebView,
                 runJavaScriptAlertPanelWithMessage message: String,
                 initiatedByFrame frame: WKFrameInfo,
                 completionHandler: @escaping () -> Void) {
        let alert = UIAlertController(title: nil, message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "OK", style: .default) { _ in
            completionHandler()
        })
        present(alert, animated: true)
    }

    func webView(_ webView: WKWebView,
                 runJavaScriptConfirmPanelWithMessage message: String,
                 initiatedByFrame frame: WKFrameInfo,
                 completionHandler: @escaping (Bool) -> Void) {
        let alert = UIAlertController(title: nil, message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "OK", style: .default) { _ in
            completionHandler(true)
        })
        alert.addAction(UIAlertAction(title: "Cancel", style: .cancel) { _ in
            completionHandler(false)
        })
        present(alert, animated: true)
    }

    // MARK: - Status Bar

    override var preferredStatusBarStyle: UIStatusBarStyle {
        return .lightContent
    }
}
