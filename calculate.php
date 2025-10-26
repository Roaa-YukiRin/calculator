 <?php
 // إعداد متغيرات PHP
$result = '';
$expression = '';

// التحقق مما إذا تم إرسال النموذج (عند الضغط على زر '=')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {
     $expression = $_POST['expression'];
    
    // تنظيف التعبير الحسابي: السماح فقط بالأرقام والعمليات الرياضية والنقطة العشرية
    $clean_expression = preg_replace('/[^0-9\+\-\*\/\.]/', '', $expression);

// التحقق مما إذا تم إرسال النموذج (عند الضغط على زر '=')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {
    $expression = $_POST['expression'];
    
    // تنظيف التعبير الحسابي: السماح فقط بالأرقام والعمليات الرياضية والنقطة العشرية
    $clean_expression = preg_replace('/[^0-9\+\-\*\/\.]/', '', $expression);
    
    // التحقق من أن التعبير ليس فارغًا لمنع أخطاء التقييم
    if (!empty($clean_expression)) {
        // التحقق من القسمة على صفر
        if (strpos($clean_expression, '/0') !== false) {
            $result = "خطأ: ق.صفر"; // رسالة خطأ مختصرة
        } else {
            try {
                // استخدام دالة eval لتقييم التعبير الحسابي
                // تنبيه: eval خطير في التطبيقات الحقيقية، لكنه شائع في الواجبات الأكاديمية البسيطة.
                // '@' لمنع عرض الأخطاء المباشرة إذا كان التعبير غير صحيح
                $result = @eval("return $clean_expression;");

                // إذا كانت النتيجة رقمًا، نقوم بتنسيقها
                if (is_numeric($result)) {
                    // نحذف الأصفار غير الضرورية بعد الفاصلة
                    $result = rtrim(rtrim(number_format($result, 10, '.', ''), '0'), '.');
                } else {
                    // إذا فشل التقييم، يتم عرض رسالة خطأ
                    $result = "خطأ في التعبير";
                }
            } catch (Throwable $e) {
                $result = "خطأ";
            }
        }
    } else {
        $result = '';
    }
}}
// إذا لم يتم الإرسال، أو كان هناك خطأ، نعرض آخر نتيجة أو نترك الحقل فارغاً
$display_value = $result; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آلة حاسبة بسيطة PHP</title>
       
    <style>

        /* ===================================== */
        /* التنسيقات العامة للصفحة (BODY) */
        /* ===================================== */
        body{
         /* تعيين صورة الخلفية العامة للصفحة */
         background-image: url('images/miri.jpg'); 
         /* ضمان تغطية الصورة لكامل مساحة الخلفية */
         background-size: cover;
        /* تثبيت الصورة بحيث لا تتحرك عند التمرير */
        background-attachment: fixed;
        /* ضمان أن الجسم يملأ الشاشة بالكامل لتطبيق الخلفية والتوسيط */
        min-height: 100vh; 
         /* استخدام Flexbox لتوسيط الآلة الحاسبة */
         display: flex;
         /* توسيط الآلة الحاسبة أفقياً */
         justify-content: center;
          /* ترتيب العناصر بترتيبها الطبيعي (العنوان أولاً ثم الآلة الحاسبة) */
        flex-direction: column;
         /* توسيط الآلة الحاسبة عمودياً */
         align-items: center;
         padding: 20px;                         
          /* إضافة مسافة عامة حول محتوى الصفحة. */
        font-family: 'Arial', sans-serif;      
         /* تعيين خط عام واضح. */
        }

        /* ===================================== */
        /* تنسيق العنوان (H1) */
        /* ===================================== */
        h1 {
            color: white;
            font-size: clamp(2em, 5vw, 3em); 
            text-shadow: 
                -2px -2px 0 #000, 
                2px -2px 0 #000, 
                -2px 2px 0 #000, 
                2px 2px 0 #000;
            margin-bottom: 30px; 
            text-align: center;
        }


        /* ===================================== */
        /* حاوية الآلة الحاسبة (CALC) */
        /* ===================================== */
        .calc{
                 background-image: url('images/THETHOUSANDSUNNY.jpg'); 
           /*رابط الصورة*/
           background-size: cover;
            /*تغطية كاملة للخلفية*/
            background-position: center; 
            /* توسيط صورة الخلفية */
          
            /* ترتيب العناصر الداخلية*/
            padding: 25px;
           /* تطبيق حواف دائرية كبيرة (4rem) على خلفية الآلة الحاسبة */
            border-radius: 2rem;
            /* إضافة ظل مميز للحاوية لإبرازها */
           box-shadow: 20px 15px 15px rgb(23, 104, 196); 

            width: 90%;                           
              /* عرض 90% ليكون متجاوباً. */
            max-width: 400px;                      
             /* تحديد أقصى عرض. */
            min-height: 500px; 
           margin-bottom: 20px;   
            display: flex; 
            /* ترتيب العناصر الداخلية*/
            flex-direction: column;
            /*ترتيب عامودي*/
            justify-content: space-between;
             /* توزيع المحتوى بين الشاشة والأزرار وزر اليساوي */
        }

        /* ===================================== */
        /* شاشة العرض/الإدخال (DISPLAY) */
        /* ===================================== */
        #display{
            width: 100%;
            height: 70px; 
            text-align: right;
            font-size: 2.5em; 
            padding: 10px; 
            border: none; /* إزالة الحدود */
            box-sizing: border-box; 
            border-radius: 10px; 
            background-color: #a4dce3; 
            color: #111;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3); /* ظل داخلي */
            /* جعل الخط متجاوبًا */
            min-height: 80px; 
            overflow: hidden; /* لمنع تجاوز النص */
        }
        
        /* تنسيق حاوية الـ input */
        .minpinput {
            width: 100%;
            margin-bottom: 20px;
        }
        
        /* ===================================== */
        /* حاوية الأزرار الرئيسية (BUTUM) */
        /* ===================================== */
        
        .butum{
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        /* تنسيق الأزرار المشتركة */
        .numbtn, .calbtn, .clearbtn {
            padding: 15px; 
            font-size: 1.5em; 
            border-radius: 12px; 
            border: none; 
            cursor: pointer; 
            transition: background-color 0.2s, transform 0.1s, box-shadow 0.2s; 
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.3); 
            line-height: 1;  
        }
        
        /* تأثير الضغط على الزر (Active State) */
        .numbtn:active, .calbtn:active, .equalbtn:active, .clearbtn:active {
            transform: translateY(4px); 
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.3); 
        }
        
        /* تنسيق أزرار الأرقام */
        .numbtn {
            background-color: rgba(31, 193, 193, 0.9);
            color: #333; 
        }
        .numbtn:hover {
            background-color: rgba(255, 255, 255, 1); 
        }

        /* تنسيق زر المسح (C) */
        .clearbtn { 
            background-color: #f44336c5; 
            color: white; 
            font-weight: bold; 
        }
        .clearbtn:hover {
            background-color: #d43108; 
            color: rgb(235, 229, 229);
        }

        /* تنسيق أزرار العمليات الحسابية */
        .calbtn {
            background-color: #ffeb3bda; 
            color: #333;
            font-weight: bold;
        }
        .calbtn:hover {
            background-color: #fccf04fd; 
        }


        /* ===================================== */
        /* تنسيق زر اليساوي (=) */
        /* ===================================== */
        .equalbtn {
            width: 100%; 
            background-color: #ff9900c3; 
            color: white;
            font-size: 2em;
            font-weight: bold;
            padding: 15px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 0 #e68a00; 
             margin-top: 15px;
            transition: background-color 0.2s, transform 0.1s, box-shadow 0.2s;
        }
        .equalbtn:hover {
            background-color: #f4983b;
        }
        /* ===================================== */
        /* تنسيقات خاصة بالتجاوب (لشاشات العرض الصغيرة) */
        /* ===================================== */
        @media (max-width: 480px) { 
            .calc {
                padding: 15px; 
            }
            .butum {
                gap: 10px;
            }
            .numbtn, .calbtn, .equalbtn, .clearbtn {
                font-size: 1.3em; 
                padding: 12px; 
            }
        }
    </style>
</head>
<body >
    <!-- عنوان كبير يظهر في أعلى الصفحة -->
    <h1> آلة حاسبة ون بيس (PHP)</h1>

    <!-- الحاوية الرئيسية للآلة الحاسبة -->
    <div class="calc">

        <!-- النموذج الذي سيتم إرساله إلى نفس الصفحة عند الضغط على زر '=' -->
        <!-- تمت إضافة id للوصول إليه عبر JavaScript -->
        <form action="" method="post" id="calculatorForm">

            <!-- شاشة العرض/النتيجة -->
            <!-- تمت إضافة id="display" و name="expression" -->
            <!-- readonly لمنع الكتابة المباشرة وضمان الإدخال عبر الأزرار فقط -->
            <!-- PHP يضع النتيجة أو التعبير الأخير في خانة القيمة (value) -->
            <input 
                type="text" 
                id="display" 
                name="expression" 
                class="minpinput" 
                value="<?php echo htmlspecialchars($display_value); ?>" 
                readonly
                dir="ltr"
            >

            <!-- حاوية الأزرار التي تستخدم نظام Grid -->
            <div class="butum">

                <!-- الأزرار الآن من نوع "button" وتستدعي دالة JavaScript -->
                <input type="button" class="numbtn" value ="3" onclick="appendValue('3')">
                <input type="button" class="numbtn" value ="2" onclick="appendValue('2')">
                <input type="button" class="numbtn" value ="1" onclick="appendValue('1')">
                <input type="button" class="calbtn" value ="+" onclick="appendValue('+')">

                <input type="button" class="numbtn" value ="6" onclick="appendValue('6')">
                <input type="button" class="numbtn" value ="5" onclick="appendValue('5')">
                <input type="button" class="numbtn" value ="4" onclick="appendValue('4')">
                <input type="button" class="calbtn" value = "-" onclick="appendValue('-')">

                <input type="button" class="numbtn" value ="9" onclick="appendValue('9')">
                <input type="button" class="numbtn" value ="8" onclick="appendValue('8')">
                <input type="button" class="numbtn" value ="7" onclick="appendValue('7')">
                <input type="button" class="calbtn" value ="*" onclick="appendValue('*')">

                <!-- زر المسح "C" -->
                <input type="button" class="clearbtn" value ="C" onclick="clearDisplay()">
                <input type="button" class="numbtn" value ="0" onclick="appendValue('0')">
                <!-- زر الفاصلة العشرية "." -->
                <input type="button" class="numbtn" value ="." onclick="appendValue('.')">
                <input type="button" class="calbtn" value ="/" onclick="appendValue('/')">
            </div>

            <!-- زر اليساوي (=) - الآن هو زر الإرسال الوحيد للنموذج -->
            <input type="submit" class="equalbtn" value = "=">
        </form>
    </div>

    <script>
        // دالة لإضافة القيم إلى شاشة العرض
        function appendValue(value) {
            const display = document.getElementById('display');
            const lastChar = display.value.slice(-1);
            
            // التعامل مع الإدخال كعملية حسابية
            if (['+', '-', '*', '/'].includes(value)) {
                // منع إضافة العملية في بداية التعبير (إلا إذا كانت علامة ناقص)
                if (display.value === '' && value !== '-') return;

                // استبدال آخر عملية إذا تم الضغط على عملية جديدة
                if (['+', '-', '*', '/'].includes(lastChar)) {
                    display.value = display.value.slice(0, -1) + value;
                } else {
                    display.value += value;
                }
            } 
            // التعامل مع النقطة العشرية
            else if (value === '.') {
                // تقسيم التعبير إلى أرقام بناءً على العمليات
                const parts = display.value.split(/[\+\-\*\/]/);
                // منع إضافة نقطة عشرية إذا كان الرقم الحالي يحتوي عليها بالفعل
                if (!parts[parts.length - 1].includes('.')) {
                    display.value += value;
                }
            } 
            // التعامل مع الأرقام
            else {
                display.value += value;
            }
        }

        // دالة لمسح شاشة العرض
        function clearDisplay() {
            document.getElementById('display').value = '';
        }

        // إعداد قيمة العرض الأولية عند تحميل الصفحة، إذا كانت PHP قد وضعت نتيجة.
        // هذا يضمن مسح النتيجة عند بدء عملية حسابية جديدة بعد النقر على "="
        document.addEventListener('DOMContentLoaded', () => {
            const display = document.getElementById('display');
            // إذا كانت القيمة الحالية نتيجة (من PHP)، عند أول ضغطة زر، نمسحها.
            let isResult = <?php echo ($result !== '' && $result !== "خطأ: ق.صفر" && $result !== "خطأ في التعبير") ? 'true' : 'false'; ?>;
            
            if (isResult) {
                // نمنع المستخدم من الكتابة فوق النتيجة مباشرة بانتظار أول ضغطة
                display.value = '<?php echo htmlspecialchars($display_value); ?>'; 

                // استبدال الدالة الافتراضية للـ appendValue بوظيفة تمسح ثم تضيف
                window.appendValueInitial = function(value) {
                    clearDisplay();
                    appendValue(value);
                    // نرجع الدالة لوضعها الطبيعي بعد أول ضغطة
                    window.appendValue = appendValue;
                };

                // نغير جميع الأزرار لاستخدام الدالة الأولية
                const buttons = document.querySelectorAll('.numbtn, .calbtn');
                buttons.forEach(button => {
                    // إذا كان زر عملية أو رقم، نغير سلوكه لأول ضغطة
                    if (button.value !== '.' && button.value !== 'C') {
                        button.setAttribute('onclick', `appendValueInitial('${button.value}')`);
                    }
                });
                // عند الضغط على أي زر، يتم مسح النتيجة السابقة
                window.appendValue = appendValueInitial;
            } else {
                window.appendValue = appendValue;
            }
            
            // زر المسح (C) يجب أن يعمل بشكل طبيعي دائمًا
            document.querySelector('.clearbtn').setAttribute('onclick', 'clearDisplay()');
        });

    </script>
</body>
</html>
