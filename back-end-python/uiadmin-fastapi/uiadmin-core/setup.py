from setuptools import setup,find_packages
# read the contents of your README file
from pathlib import Path
this_directory = Path(__file__).parent
long_description = (this_directory / "README.md").read_text()
 
setup(
    name='Uiadmin-FastApi',
    version='1.0.2',
    url='http://uiadmin.net',
    license='Apache2',
    author='jry',
    author_email='ijry@qq.com',
    description='uiadmin的python-fastapi实现',
    long_description=long_description,
    long_description_content_type='text/markdown',
    packages=find_packages(), # 使用find_packages才会发布插件下的子目录
    zip_safe=False,
    include_package_data=True,
    platforms='any',
    install_requires=[
        'fastapi'
    ],
    classifiers=[
        'Environment :: Web Environment',
        'Intended Audience :: Developers',
        'License :: OSI Approved :: Apache Software License',
        'Operating System :: OS Independent',
        'Programming Language :: Python',
        'Topic :: Internet :: WWW/HTTP :: Dynamic Content',
        'Topic :: Software Development :: Libraries :: Python Modules'
    ]
)
