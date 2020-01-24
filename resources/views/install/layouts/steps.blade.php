        <ul class="step">
          <li class="step__divider"></li>
          <li class="step__item {!! Request::is('install/final') ? ' active' : null !!}"><i class="step__icon database"></i></li>

          <li class="step__divider"></li>
          <li class="step__item {!! Request::is('install/config') ? ' active' : null !!}"><i class="step__icon update"></i></li>

          
          <li class="step__divider"></li>
          <li class="step__item {!! Request::is('install/requirements') ? ' active' : null !!}"><i class="step__icon requirements"></i></li>

          <li class="step__divider"></li>
          <li class="step__item {!! Request::is('install/permissions') ? ' active' : null !!}"><i class="step__icon permissions"></i></li>

          <li class="step__divider"></li>
          <li class="step__item {!! Request::is('install') ? ' active' : null !!}"><i class="step__icon welcome"></i></li>
          <li class="step__divider"></li>
        </ul>