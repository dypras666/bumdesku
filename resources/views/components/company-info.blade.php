<div class="company-info company-info-{{ $size }}">
    @if($showLogo && !empty($companyInfo['logo']))
        <div class="company-logo mb-2">
            <img src="{{ $companyInfo['logo'] }}" 
                 alt="{{ $companyInfo['name'] }}" 
                 class="img-fluid 
                    @if($size === 'small') max-height-50
                    @elseif($size === 'large') max-height-150
                    @else max-height-100
                    @endif">
        </div>
    @endif

    <div class="company-details">
        <h4 class="company-name 
            @if($size === 'small') h6
            @elseif($size === 'large') h2
            @else h4
            @endif font-weight-bold text-primary mb-1">
            {{ $companyInfo['name'] ?? 'BUMDES' }}
        </h4>

        @if(!empty($companyInfo['village_name']))
            <p class="village-name 
                @if($size === 'small') small
                @elseif($size === 'large') h5
                @else text-muted
                @endif mb-1">
                {{ $companyInfo['village_name'] }}
            </p>
        @endif

        @if($showAddress && !empty($companyInfo['village_address']))
            <div class="company-address mb-2">
                <small class="text-muted">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $companyInfo['village_address'] }}
                </small>
            </div>
        @endif

        @if($showContact)
            <div class="company-contact">
                @if(!empty($companyInfo['village_phone']))
                    <small class="text-muted d-block">
                        <i class="fas fa-phone"></i>
                        {{ $companyInfo['village_phone'] }}
                    </small>
                @endif

                @if(!empty($companyInfo['village_email']))
                    <small class="text-muted d-block">
                        <i class="fas fa-envelope"></i>
                        {{ $companyInfo['village_email'] }}
                    </small>
                @endif
            </div>
        @endif

        @if(!empty($companyInfo['director_name']))
            <div class="director-info mt-2">
                <small class="text-muted">
                    <strong>Kepala Bumdes:</strong> {{ $companyInfo['director_name'] }}
                    @if(!empty($companyInfo['director_nip']))
                        <br><strong>NIP:</strong> {{ $companyInfo['director_nip'] }}
                    @endif
                </small>
            </div>
        @endif
    </div>
</div>

<style>
.max-height-50 { max-height: 50px; }
.max-height-100 { max-height: 100px; }
.max-height-150 { max-height: 150px; }
</style>