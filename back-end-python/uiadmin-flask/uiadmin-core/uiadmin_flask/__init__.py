#coding:utf8
from functools import wraps
from .utils import jsonres
from .Uiadmin import Uiadmin
from .CoreController import CoreController
from . import util as util

__all__ = ['wraps', 'jsonres', 'Uiadmin', 'CoreController', 'util']

# @app.before_request
# def basic_authentication():
#     if request.method.lower() == 'options':
#         return jsonres({});

