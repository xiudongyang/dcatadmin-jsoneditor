<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        @if ($diff)
            <style>
                #{{$id}} .different_element {
                    background-color: #acee61;
                }
                #{{$id}} .different_element div.jsoneditor-field,
                #{{$id}} .different_element div.jsoneditor-value {
                     color: red;
                 }

                #standard .different_element {
                    background-color: pink;
                }
                #standard .different_element div.jsoneditor-field,
                #standard .different_element div.jsoneditor-value {
                    color: red;
                }
            </style>
            <div id="wrapper">
                <div id="standard" style="width: 45%; height: 100%; display: inline-block"></div>
                <div id="{{$id}}" style="width: 45%; height: 100%; display: inline-block"></div>
            </div>
        @else
            <div id="{{$id}}" style="width: 100%; height: 100%;"></div>
        @endif
        <input type="hidden" id="{{$id}}_input" name="{{$name}}" value="{{ old($column, $value) }}" />
        @include('admin::form.help-block')

    </div>
</div>
