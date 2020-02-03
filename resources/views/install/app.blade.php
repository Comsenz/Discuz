<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">

    <style>
      /* cyrillic-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0Udc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      }
      /* cyrillic */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0ddc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }
      /* greek-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0Vdc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+1F00-1FFF;
      }
      /* greek */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0adc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+0370-03FF;
      }
      /* vietnamese */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0Wdc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
      }
      /* latin-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0Xdc1GAK6bt6o.woff2) format('woff2');
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 400;
          src: local('Open Sans Italic'), local('OpenSans-Italic'), url(assets/fonts/mem6YaGs126MiZpBA-UFUK0Zdc1GAK6b.woff2) format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }
      /* cyrillic-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhmIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      }
      /* cyrillic */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhvIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }
      /* greek-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhnIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+1F00-1FFF;
      }
      /* greek */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhoIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+0370-03FF;
      }
      /* vietnamese */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhkIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
      }
      /* latin-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhlIqOxjaPXZSk.woff2) format('woff2');
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
          font-family: 'Open Sans';
          font-style: italic;
          font-weight: 700;
          src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(assets/fonts/memnYaGs126MiZpBA-UFUKWiUNhrIqOxjaPX.woff2) format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }
      /* cyrillic-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFWJ0bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      }
      /* cyrillic */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFUZ0bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }
      /* greek-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFWZ0bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+1F00-1FFF;
      }
      /* greek */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFVp0bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+0370-03FF;
      }
      /* vietnamese */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFWp0bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
      }
      /* latin-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFW50bf8pkAp6a.woff2) format('woff2');
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 400;
          src: local('Open Sans Regular'), local('OpenSans-Regular'), url(assets/fonts/mem8YaGs126MiZpBA-UFVZ0bf8pkAg.woff2) format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }
      /* cyrillic-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOX-hpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      }
      /* cyrillic */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOVuhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }
      /* greek-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOXuhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+1F00-1FFF;
      }
      /* greek */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOUehpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0370-03FF;
      }
      /* vietnamese */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOXehpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
      }
      /* latin-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOXOhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 600;
          src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'), url(assets/fonts/mem5YaGs126MiZpBA-UNirkOUuhpKKSTjw.woff2) format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }
      /* cyrillic-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOX-hpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
      }
      /* cyrillic */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOVuhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }
      /* greek-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOXuhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+1F00-1FFF;
      }
      /* greek */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOUehpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0370-03FF;
      }
      /* vietnamese */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOXehpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
      }
      /* latin-ext */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOXOhpKKSTj5PW.woff2) format('woff2');
          unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
      }
      /* latin */
      @font-face {
          font-family: 'Open Sans';
          font-style: normal;
          font-weight: 700;
          src: local('Open Sans Bold'), local('OpenSans-Bold'), url(assets/fonts/mem5YaGs126MiZpBA-UN7rgOUuhpKKSTjw.woff2) format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
      }


      body {
        background: #fff;
        margin: 0;
        padding: 0;
        line-height: 1.5;
      }
      body, input, button {
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        color: #7E96B3;
      }
      .container {
        max-width: 515px;
        margin: 0 auto;
        padding: 100px 30px;
        text-align: center;
      }
      a {
        color: #3778e7;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
      }

      h1 {
        margin-bottom: 40px;
      }
      h2 {
        font-size: 28px;
        font-weight: normal;
        color: #3C5675;
        margin-bottom: 0;
      }

      form {
        margin-top: 40px;
      }
      .FormGroup {
        margin-bottom: 20px;
      }
      .FormGroup .FormField:first-child input {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
      }
      .FormGroup .FormField:last-child input {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
      }
      .FormField input {
        background: #EDF2F7;
        margin: 0 0 1px;
        border: 2px solid transparent;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
        width: 100%;
        padding: 15px 15px 15px 180px;
        box-sizing: border-box;
      }
      .FormField input:focus {
        border-color: #3778e7;
        background: #fff;
        color: #444;
        outline: none;
      }
      .FormField label {
        float: left;
        width: 160px;
        text-align: right;
        margin-right: -160px;
        position: relative;
        margin-top: 18px;
        font-size: 14px;
        pointer-events: none;
        opacity: 0.7;
      }
      button {
        background: #3C5675;
        color: #fff;
        border: 0;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        padding: 15px 30px;
        -webkit-appearance: none;
      }
      button[disabled] {
        opacity: 0.5;
      }

      #error {
        background: #2d36d8;
        color: #fff;
        padding: 15px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
      }

      .animated {
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;

        -webkit-animation-duration: 0.5s;
        animation-duration: 0.5s;

        animation-delay: 1.7s;
        -webkit-animation-delay: 1.7s;
      }
      @-webkit-keyframes fadeIn {
        0% {opacity: 0}
        100% {opacity: 1}
      }
      @keyframes fadeIn {
        0% {opacity: 0}
        100% {opacity: 1}
      }
      .fadeIn {
        -webkit-animation-name: fadeIn;
        animation-name: fadeIn;
      }

      .Problems {
        margin-top: 50px;
      }
      .Problems .Problem:first-child {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
      }
      .Problems .Problem:last-child {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
      }
      .Problem {
        background: #EDF2F7;
        margin: 0 0 1px;
        padding: 20px 25px;
        text-align: left;
      }
      .Problem-message {
        font-size: 16px;
        color: #3C5675;
        font-weight: normal;
        margin: 0;
      }
      .Problem-detail {
        font-size: 13px;
        margin: 5px 0 0;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <h1>
        <img src="{{$logo}}" title="logo" />
      </h1>

      <div class="animated fadeIn">
        @yield('content')
      </div>
    </div>
  </body>
</html>
