# 发布密钥获取https://www.nuget.org/account/apikeys

dotnet pack
dotnet nuget push bin/Debug/UiAdmin.Core.1.0.0.nupkg --api-key $1 --source https://api.nuget.org/v3/index.json
