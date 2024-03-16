@extends('layout')
@section('content')
    <form>
        <div class="row">
            <div class="col s12">
                <div class="input-field inline" style="width: 50%">


                </div>

            </div>
        </div>
        <nav class="no-space">
            <div class="max field border label small-round left-round">
                <input id="ip" type="text" class="validate" name="ip" value="<?php echo htmlspecialchars($ip);?>">
                <label for="ip">Ip Address</label>
            </div>
            <button class="large small-round right-round red6" type="submit">Submit

            </button>
        </nav>
        @if($errorMessages)
        <article class="border">
            <h5>Error</h5>
            <div><ul>
                @foreach($errorMessages as $message)
                    <li><?php echo $message?></li>
                @endforeach
            </ul></div>

        </article>
        @endif

        @if($ipInfos)
            <div class="grid">
            @foreach($ipInfos as $provider=>$ipInfo)
                <div class="s12 m6">
                    <article class="border">
                        <h5>{{$provider}}</h5>
                        <p></p>
                        <div>
                            @foreach ($ipInfo as $key=>$item)
                                <div class="grid">
                                    <div class="s4">{{$item['label']}}:</div>
                                    <div class="s8"> @if($key==='country')
                                            <span class="fi fi-{{strtolower($countryCode)}}"></span>
                                        @endif
                                        <span class="break-word">{{$item['value']}}</span>
                                    </div>

                                </div>
                                <div class="small-divider"></div>
                            @endforeach
                        </div>
                    </article>
                </div>
            @endforeach
            </div>
        @endif

    </form>
@endsection
